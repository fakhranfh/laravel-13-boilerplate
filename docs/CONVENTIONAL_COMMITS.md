# Conventional Commits

> Spesifikasi untuk memberikan makna yang dapat dibaca oleh manusia dan mesin pada commit message.

**Referensi:** [Ringkasan](https://www.conventionalcommits.org/en/v1.0.0/#summary) · [Spesifikasi Lengkap](https://www.conventionalcommits.org/en/v1.0.0/#specification) · [Kontribusi](https://github.com/conventional-commits/conventionalcommits.org)

---

## Format Commit

```
<type>[optional scope]: <description>

[optional body]

[optional footer(s)]
```

### Elemen Struktural

| Elemen | Keterangan |
|---|---|
| `fix:` | Memperbaiki bug — korelasi dengan `PATCH` di SemVer |
| `feat:` | Menambah fitur baru — korelasi dengan `MINOR` di SemVer |
| `BREAKING CHANGE:` | Perubahan yang merusak kompatibilitas — korelasi dengan `MAJOR` di SemVer |
| Tipe lain | `build`, `chore`, `ci`, `docs`, `style`, `refactor`, `perf`, `test`, dll. |

> **Scope** bersifat opsional dan ditulis dalam tanda kurung, contoh: `feat(parser): add ability to parse arrays`

---

## Contoh

```bash
# Fitur baru biasa
feat: allow provided config object to extend other configs

# Breaking change dengan footer
feat: allow provided config object to extend other configs

BREAKING CHANGE: `extends` key in config file is now used for extending other config files

# Breaking change dengan tanda seru
feat!: send an email to the customer when a product is shipped

# Breaking change dengan scope
feat(api)!: send an email to the customer when a product is shipped

# Breaking change dengan ! dan footer
feat!: drop support for Node 6

BREAKING CHANGE: use JavaScript features not available in Node 6.

# Tanpa body
docs: correct spelling of CHANGELOG

# Dengan scope
feat(lang): add Polish language

# Multi-paragraph body dan beberapa footer
fix: prevent racing of requests

Introduce a request id and a reference to latest request. Dismiss
incoming responses other than from latest request.

Remove timeouts which were used to mitigate the racing issue but are
obsolete now.

Reviewed-by: Z
Refs: #123

# Revert commit
revert: let us never again speak of the noodle incident

Refs: 676104e, a215868
```

---

## Spesifikasi

1. Commit **HARUS** diawali dengan tipe berupa kata benda (`feat`, `fix`, dll.), diikuti scope opsional, `!` opsional, lalu titik dua dan spasi.
2. Tipe `feat` **HARUS** digunakan saat commit menambah fitur baru.
3. Tipe `fix` **HARUS** digunakan saat commit memperbaiki bug.
4. Scope **BOLEH** diberikan setelah tipe, berupa kata benda dalam tanda kurung, contoh: `fix(parser):`.
5. Deskripsi **HARUS** langsung mengikuti titik dua setelah tipe/scope.
6. Body yang lebih panjang **BOLEH** diberikan setelah deskripsi singkat, diawali satu baris kosong.
7. Body berbentuk bebas dan **BOLEH** terdiri dari beberapa paragraf.
8. Satu atau lebih footer **BOLEH** diberikan satu baris kosong setelah body, dengan format `token: value` atau `token #value`.
9. Token footer **HARUS** menggunakan `-` sebagai pengganti spasi (misal: `Acked-by`), kecuali `BREAKING CHANGE`.
10. Nilai footer **BOLEH** mengandung spasi dan baris baru; parsing berhenti saat token footer valid berikutnya ditemukan.
11. Breaking change **HARUS** ditandai di prefix tipe/scope atau sebagai entri di footer.
12. Jika di footer, breaking change **HARUS** berupa `BREAKING CHANGE: <deskripsi>`.
13. Jika di prefix, breaking change **HARUS** ditandai dengan `!` sebelum `:`. Jika `!` digunakan, `BREAKING CHANGE:` di footer boleh dihilangkan.
14. Tipe selain `feat` dan `fix` **BOLEH** digunakan, contoh: `docs: update ref docs`.
15. Informasi dalam Conventional Commits **TIDAK BOLEH** diperlakukan case-sensitive, kecuali `BREAKING CHANGE` yang **HARUS** huruf kapital.
16. `BREAKING-CHANGE` **HARUS** dianggap sinonim dengan `BREAKING CHANGE` saat digunakan sebagai token footer.

---

## Manfaat

- Otomatis menghasilkan CHANGELOG.
- Otomatis menentukan kenaikan versi semantik berdasarkan tipe commit.
- Mengomunikasikan sifat perubahan kepada tim, publik, dan stakeholder.
- Memicu proses build dan publish.
- Memudahkan kontributor menjelajahi riwayat commit yang lebih terstruktur.

---

## FAQ

**Bagaimana menangani commit di fase pengembangan awal?**
Lanjutkan seolah produk sudah dirilis. Orang lain tetap perlu tahu apa yang diperbaiki atau berubah.

**Apakah tipe commit huruf besar atau kecil?**
Bebas, tapi harus konsisten.

**Bagaimana jika commit sesuai lebih dari satu tipe?**
Buat beberapa commit terpisah. Ini mendorong commit yang lebih terorganisir.

**Apakah ini menghambat pengembangan cepat?**
Tidak — ini menghambat pengembangan cepat yang tidak terorganisir, dan membantu bergerak lebih cepat dalam jangka panjang.

**Bagaimana hubungannya dengan SemVer?**
- `fix` → `PATCH`
- `feat` → `MINOR`
- `BREAKING CHANGE` → `MAJOR`

**Bagaimana jika salah tipe commit?**
- Belum merge: gunakan `git rebase -i` untuk mengedit riwayat commit.
- Sudah release: sesuaikan dengan tools dan proses yang digunakan.
- Tipe di luar spec: tidak fatal, hanya akan diabaikan oleh tools berbasis spec.

**Apakah semua kontributor harus mengikuti spec ini?**
Tidak wajib. Workflow berbasis squash memungkinkan lead maintainer merapikan commit message saat merge.

**Bagaimana menangani revert commit?**
Gunakan tipe `revert` dengan footer yang mereferensikan SHA commit yang di-revert:

```
revert: let us never again speak of the noodle incident

Refs: 676104e, a215868
```
