#!/usr/bin/env bash
# =============================================================================
# deploy.sh — Script deploy untuk sch_web (CodeIgniter 4)
#
# Cara pakai:
#   chmod +x deploy.sh        # sekali saja
#   ./deploy.sh               # update biasa
#   ./deploy.sh --fresh       # install + seed (server baru / reset total)
#
# Pasang git hook agar otomatis jalan setiap `git pull`:
#   cp deploy.sh .git/hooks/post-merge
#   chmod +x .git/hooks/post-merge
# =============================================================================

set -euo pipefail

# ── Warna output ─────────────────────────────────────────────────────────────
GREEN='\033[0;32m'; YELLOW='\033[1;33m'; RED='\033[0;31m'; NC='\033[0m'
info()    { echo -e "${GREEN}[deploy]${NC} $*"; }
warn()    { echo -e "${YELLOW}[deploy]${NC} $*"; }
error()   { echo -e "${RED}[deploy]${NC} $*" >&2; }

# ── Deteksi binary PHP ───────────────────────────────────────────────────────
PHP=$(command -v php8.1 2>/dev/null || command -v php8.2 2>/dev/null || command -v php 2>/dev/null || true)
if [[ -z "$PHP" ]]; then
    error "PHP tidak ditemukan di PATH. Abort."
    exit 1
fi
info "Menggunakan PHP: $($PHP -r 'echo PHP_VERSION;')"

# ── Direktori project ────────────────────────────────────────────────────────
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

# ── Flag --fresh ─────────────────────────────────────────────────────────────
FRESH=false
if [[ "${1:-}" == "--fresh" ]]; then
    FRESH=true
    warn "Mode --fresh aktif: akan menjalankan migrate:fresh + seed."
fi

# ─────────────────────────────────────────────────────────────────────────────
# 1. Git pull (dilewati jika dipanggil dari post-merge hook)
# ─────────────────────────────────────────────────────────────────────────────
if [[ "${GIT_POST_MERGE:-}" != "1" ]]; then
    info "Menarik perubahan terbaru dari remote..."
    git pull --ff-only
fi

# ─────────────────────────────────────────────────────────────────────────────
# 2. Composer install (hanya jika composer.lock berubah)
# ─────────────────────────────────────────────────────────────────────────────
if git diff --name-only HEAD@{1} HEAD 2>/dev/null | grep -q "composer\.lock"; then
    info "composer.lock berubah — menjalankan composer install..."
    composer install --no-dev --optimize-autoloader --no-interaction
else
    info "composer.lock tidak berubah, skip composer install."
fi

# ─────────────────────────────────────────────────────────────────────────────
# 3. Cek file .env
# ─────────────────────────────────────────────────────────────────────────────
if [[ ! -f ".env" ]]; then
    warn ".env tidak ditemukan."
    if [[ -f "env" ]]; then
        warn "Menyalin file 'env' ke '.env' — harap sesuaikan isinya!"
        cp env .env
    else
        error "File env/template tidak ada. Buat .env secara manual lalu jalankan ulang."
        exit 1
    fi
fi

# ─────────────────────────────────────────────────────────────────────────────
# 4. Migrasi database
# ─────────────────────────────────────────────────────────────────────────────
if [[ "$FRESH" == true ]]; then
    warn "Menjalankan migrate:fresh (semua tabel akan di-drop + dibuat ulang)..."
    $PHP spark migrate:fresh --no-interaction
    info "Menjalankan seeder awal..."
    $PHP spark db:seed DatabaseSeeder
else
    info "Menjalankan migrasi baru (jika ada)..."
    $PHP spark migrate --no-interaction
fi

# ─────────────────────────────────────────────────────────────────────────────
# 5. Bersihkan cache CI4
# ─────────────────────────────────────────────────────────────────────────────
info "Membersihkan cache..."
$PHP spark cache:clear

# ─────────────────────────────────────────────────────────────────────────────
# 6. Perbaiki permission folder writable
# ─────────────────────────────────────────────────────────────────────────────
if [[ -d "writable" ]]; then
    chmod -R 775 writable
    info "Permission writable/ diperbaiki."
fi

# ─────────────────────────────────────────────────────────────────────────────
echo ""
info "Deploy selesai."
