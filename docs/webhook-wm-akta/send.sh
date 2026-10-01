#!/usr/bin/env bash
# ---------------------------------------------------------------------------
# Contoh pemanggilan webhook WM Service.
#
# Pakai:
#   ./send.sh                                    # kirim status success (default)
#   ./send.sh failed                             # kirim status failed
#   ./send.sh success request-minimal.json       # kirim file contoh lain
#
# Ganti BASE_URL dan TOKEN di bawah, atau set lewat environment variable:
#   BASE_URL=http://ip:port TOKEN=xxxx ./send.sh
# ---------------------------------------------------------------------------

set -euo pipefail

BASE_URL="${BASE_URL:-http://localhost:8000}"
TOKEN="${TOKEN:-ISI_TOKEN_WM_SERVICE_DARI_TABEL_ACCESS_TOKEN}"
STATUS="${1:-success}"
PAYLOAD_FILE="${2:-request-${STATUS}.json}"

# lokasi file contoh = folder tempat script ini berada
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PAYLOAD_PATH="${SCRIPT_DIR}/${PAYLOAD_FILE}"

if [[ ! -f "$PAYLOAD_PATH" ]]; then
    echo "File payload tidak ditemukan: $PAYLOAD_PATH" >&2
    exit 1
fi

echo "POST ${BASE_URL}/api/webhook/wm/akta"
echo "Payload (${PAYLOAD_FILE}):"
cat "$PAYLOAD_PATH"
echo ""

curl -sS -w "\nHTTP %{http_code}\n" \
    -X POST "${BASE_URL}/api/webhook/wm/akta" \
    -H "Authorization: Bearer ${TOKEN}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    --data-binary "@${PAYLOAD_PATH}"
