<!doctype html><html lang="id"><head><meta charset="utf-8"><title>{{ $kind }} {{ $leave->request_number }}</title><style>
@if(!empty($pdfBookmanRegular))
@font-face{font-family:'CutiBookman';src:url('data:font/truetype;base64,{{ $pdfBookmanRegular }}') format('truetype');font-weight:400;font-style:normal}
@endif
@if(!empty($pdfBookmanBold))
@font-face{font-family:'CutiBookman';src:url('data:font/truetype;base64,{{ $pdfBookmanBold }}') format('truetype');font-weight:700;font-style:normal}
@endif
@page{size:A4 portrait;margin:20mm 20mm 20mm 30mm}
@if($kind === 'form')
@page{size:A4 portrait;margin:12mm 20mm 12mm 30mm}
@elseif($kind === 'bundle')
@page :first{margin-top:12mm;margin-bottom:12mm}
@endif
*{box-sizing:border-box}
body{font-family:'CutiBookman','Bookman Old Style','URW Bookman','DejaVu Serif',serif;font-size:12pt;line-height:1.0;color:#111;margin:0}
h1,h2,p{margin:0}table{border-collapse:collapse;width:100%;table-layout:fixed}td,th{vertical-align:top;overflow-wrap:break-word}
.page{width:100%;position:relative}.page+.page{page-break-before:always}
.center{text-align:center}.right{text-align:right}.bold{font-weight:bold}.upper{text-transform:uppercase}
.space4{height:3mm}.space3{height:.7mm}.space2{height:1mm}
.form-table td,.form-table th{border:.6px solid #333;padding:.45mm .65mm;font-size:12pt;line-height:1.0}
.form-table .section-title{font-weight:bold;background:#fafafa;padding:.45mm .65mm;font-size:12pt;line-height:1.0}
.form-caption{text-align:center;font-size:12pt;font-weight:bold;margin:2mm 0}
.form-page .header-date{text-align:right}
.form-page .form-table.tight td{padding:.4mm .65mm;line-height:1.0}
.form-page .form-table .section-title{padding:.4mm .7mm}
.form-page .sign-space{height:7mm}.form-page .address-cell{height:10mm}.form-page .signature-cell{height:15mm}
.form-page .approval-layout{width:100%;table-layout:fixed}.form-page .approval-layout td{border:none;padding:0;vertical-align:top}
.form-page .approval-table{width:100%;table-layout:fixed;page-break-inside:avoid}
.form-page .approval-table .section-title{font-size:10pt;line-height:1.0;padding:.5mm .6mm}
.form-page .approval-table .approval-choice{font-size:9pt;line-height:1.0;padding:.4mm .5mm;white-space:normal}
.form-page .approval-table .approval-signature{font-size:9pt;line-height:1.0;height:12mm;text-align:center;white-space:normal}
.form-page .approval-table .approval-signature .sign-space{height:7mm}
.checkbox{display:inline-block;border:.8px solid #111;width:2.4mm;height:2.4mm;text-align:center;line-height:2.4mm;font-size:7pt}
.signature-name{font-weight:bold;text-decoration:underline}
.docid{font-size:7pt;color:#555;text-align:right;margin-top:1mm}
/* permission and application */
/* v1.0.25 - Kop naskah dinas: garis utama di atas, garis pendamping di bawah.
   Dua elemen riil dipakai agar Dompdf tidak bergantung pada pseudo-elements. */
.letterhead{border-bottom:0;margin-bottom:8.5mm;padding-bottom:0;min-height:27mm}
.permission-page .letterhead-rules{clear:both;padding-top:2.3mm}
.permission-page .letterhead-rule-primary{border-top:3pt solid #111;height:0;line-height:0;font-size:0}
.permission-page .letterhead-rule-secondary{border-top:.85pt solid #111;height:0;margin-top:1.2mm;line-height:0;font-size:0}
.logo{float:left;width:18mm;height:20mm;object-fit:contain;margin:0 4mm 0 0}
.letterhead-text{text-align:center}
.letterhead-text .gov{font-size:12pt;font-weight:normal;line-height:1.0}
.letterhead-text .agency{font-size:16pt;font-weight:bold;line-height:1.0}
.letterhead-text .addr{font-size:9pt;line-height:1.0}
.letter-title{font-size:12pt;font-weight:bold;text-decoration:underline;text-align:center}
.letter-number{font-size:12pt;text-align:center;margin-top:1mm}
.letter-info{table-layout:auto;width:88%;border-collapse:collapse}
.letter-info td{font-size:12pt;padding:.7mm .8mm;line-height:1.15}
.letter-info .label{width:33mm;padding-right:.3mm}
.letter-info .sep{width:4.5mm;text-align:center;padding-left:0;padding-right:0}
.letter-info .value{padding-left:.35mm}
.body-letter{font-size:12pt;line-height:1.15;text-align:justify}
.body-letter p{margin-bottom:4mm;text-align:justify}
.body-letter ol{margin:0 0 4mm 6mm;padding-left:5mm}
.body-letter li{padding-left:1mm;text-align:justify}
.signature-block{width:48%;margin-left:52%;font-size:12pt;line-height:1.15}
.sig-height{height:25mm}
.permit-title-area{margin:0 0 8mm}
.text-indent{text-indent:6mm}
.permission-page .letter-info{margin-left:1mm}
.application-page .application-title{margin:8mm 0 10mm!important;text-decoration:none}
.application-page .application-body{font-size:12pt!important;line-height:1.15!important}
.application-page .letter-info{margin-left:12mm;width:84%}
.application-page .application-signature{margin-top:16mm!important}
.application-page .application-docid{margin-top:7mm!important}
/* Tingkat kepadatan: margin kertas tidak pernah dikurangi. */
.form-page.density-1 .form-table.tight td{padding:.25mm .5mm;font-size:11.5pt}
.form-page.density-1 .form-table .section-title{padding:.3mm .5mm;font-size:11.5pt}
.form-page.density-1 .space3{height:.3mm}
.form-page.density-2 .form-table.tight td{padding:.2mm .45mm;font-size:10.5pt}
.form-page.density-2 .form-table .section-title{padding:.25mm .45mm;font-size:10.5pt}
.form-page.density-2 .space3{height:0}
.form-page.density-1 .approval-table .approval-choice,.form-page.density-2 .approval-table .approval-choice{font-size:8.5pt}
.form-page.density-1 .approval-table .approval-signature,.form-page.density-2 .approval-table .approval-signature{font-size:8.5pt}
.permission-page.density-1 .body-letter p,.application-page.density-1 .body-letter p{margin-bottom:3mm}
.permission-page.density-2 .body-letter p,.application-page.density-2 .body-letter p{margin-bottom:2mm}
.permission-page.density-1 .permit-title-area,.permission-page.density-2 .permit-title-area{margin:0 0 5mm}
.permission-page.density-1 .sig-height,.application-page.density-1 .sig-height{height:20mm}
.permission-page.density-2 .sig-height,.application-page.density-2 .sig-height{height:16mm}
.application-page.density-1 .application-signature{margin-top:12mm!important}
.application-page.density-2 .application-signature{margin-top:8mm!important}
/* Kop: proporsi 12:16 = 3:4; kedua baris memakai keluarga Bookman. */
.letterhead-text .gov{font-size:12pt;font-weight:normal;line-height:1.0}
.letterhead-text .agency{font-size:16pt;font-weight:bold;line-height:1.0}
.form-page .approval-table{width:100%;table-layout:fixed;page-break-inside:avoid}
/* Adaptasi khusus formulir tabel untuk mesin Dompdf. */
.form-page.density-3 .form-table.tight td{padding:.15mm .35mm;font-size:9.75pt;line-height:1.0}
.form-page.density-3 .form-table .section-title{padding:.18mm .35mm;font-size:9.75pt;line-height:1.0}
.form-page.density-4 .form-table.tight td{padding:.10mm .30mm;font-size:9.1pt;line-height:1.0}
.form-page.density-4 .form-table .section-title{padding:.12mm .30mm;font-size:9.1pt;line-height:1.0}
.form-page.density-5 .form-table.tight td{padding:.08mm .25mm;font-size:8.5pt;line-height:1.0}
.form-page.density-5 .form-table .section-title{padding:.10mm .25mm;font-size:8.5pt;line-height:1.0}
.form-page.density-3 .space3,.form-page.density-4 .space3,.form-page.density-5 .space3{height:0}
.form-page.density-3 .form-addressee,.form-page.density-4 .form-addressee,.form-page.density-5 .form-addressee{font-size:10pt;line-height:1.0}
.form-page.density-3 .form-caption,.form-page.density-4 .form-caption,.form-page.density-5 .form-caption{font-size:10.5pt;margin:.5mm 0}
.form-page.density-3 .sign-space,.form-page.density-4 .sign-space,.form-page.density-5 .sign-space{height:3mm}
.form-page.density-3 .address-cell,.form-page.density-4 .address-cell,.form-page.density-5 .address-cell{height:8mm}
.form-page.density-3 .signature-cell,.form-page.density-4 .signature-cell,.form-page.density-5 .signature-cell{height:8mm}
.form-page.density-3 .approval-table .approval-choice,.form-page.density-4 .approval-table .approval-choice,.form-page.density-5 .approval-table .approval-choice{font-size:8pt;line-height:1.0;padding:.15mm .25mm}
.form-page.density-3 .approval-table .approval-signature,.form-page.density-4 .approval-table .approval-signature,.form-page.density-5 .approval-table .approval-signature{font-size:8pt;line-height:1.0;padding:.15mm .25mm;height:9mm}
.form-page.density-3 .approval-table .approval-signature .sign-space,.form-page.density-4 .approval-table .approval-signature .sign-space,.form-page.density-5 .approval-table .approval-signature .sign-space{height:3mm}
.permission-page.density-3 .body-letter p,.application-page.density-3 .body-letter p{margin-bottom:1.5mm}
.permission-page.density-3 .permit-title-area{margin:0 0 4mm}
.permission-page.density-3 .letterhead{margin-bottom:6mm;min-height:26mm}
.permission-page.density-3 .sig-height,.application-page.density-3 .sig-height{height:13mm}
.application-page.density-3 .application-title{margin:5mm 0 7mm!important}
.application-page.density-3 .application-signature{margin-top:6mm!important}

/* Formulir Word Kabupaten Subang: aturan hanya untuk _form.blade.php.
   Dokumen _permission dan _application tidak terpengaruh. */
.form-page.word-cuti-form .form-addressee{margin:2mm 0 0;table-layout:fixed;line-height:1.05}
.form-page.word-cuti-form .header-date{font-size:10pt}
.form-page.word-cuti-form .form-caption{font-weight:normal;font-size:11pt;margin:2.2mm 0 3mm;text-align:center}
.form-page.word-cuti-form .word-table{width:100%;border-collapse:collapse;margin:0 0 2.7mm;table-layout:fixed;page-break-inside:avoid}
.form-page.word-cuti-form .word-table td{padding:.8mm .8mm;font-size:10.2pt;line-height:1.04;border:.55pt solid #222;vertical-align:top;overflow-wrap:break-word}
.form-page.word-cuti-form .word-table .section-title{font-size:10.2pt;line-height:1.04;background:#fff;font-weight:normal;padding:.65mm .8mm}
.form-page.word-cuti-form .word-subline{font-size:8pt;white-space:nowrap}
.form-page.word-cuti-form .word-reason-text{min-height:5mm}
.form-page.word-cuti-form .word-history td{padding:.45mm .7mm}
.form-page.word-cuti-form .word-address{margin-bottom:2.7mm}
.form-page.word-cuti-form .word-applicant-sign{height:20mm;line-height:1.06}
.form-page.word-cuti-form .word-address-text{line-height:1.12}
.form-page.word-cuti-form .word-sign-space{height:8mm}
.form-page.word-cuti-form .word-decision .section-title{font-size:10.2pt;line-height:1.04}
.form-page.word-cuti-form .word-decision td.word-decision-choice{font-size:8.4pt;line-height:1.05;padding:.8mm .4mm;white-space:normal}
.form-page.word-cuti-form .word-decision td.word-decision-position{font-size:8.7pt;line-height:1.07;height:5.5mm}
.form-page.word-cuti-form .word-decision td.word-decision-sign{font-size:9.1pt;line-height:1.05;height:19mm;vertical-align:bottom;padding-bottom:.65mm}
.form-page.word-cuti-form .word-decision td.word-approval-empty{border-right:.55pt solid #222}
.form-page.word-cuti-form .word-decision .word-sign-space{height:9mm}
.form-page.word-cuti-form .word-decision{margin-bottom:2.7mm}
.form-page.word-cuti-form .docid{margin-top:1mm;font-size:7pt}
/* Penyesuaian kepadatan Dompdf bila isi panjang; struktur Word dan margin tidak berubah. */
.form-page.word-cuti-form.density-1 .word-table{margin-bottom:2mm}
.form-page.word-cuti-form.density-1 .word-table td{padding:.65mm .65mm;font-size:9.75pt}
.form-page.word-cuti-form.density-1 .word-table .section-title{font-size:9.75pt}
.form-page.word-cuti-form.density-1 .word-applicant-sign{height:18mm}
.form-page.word-cuti-form.density-1 .word-decision td.word-decision-sign{height:17mm}
.form-page.word-cuti-form.density-2 .word-table{margin-bottom:1.7mm}
.form-page.word-cuti-form.density-2 .word-table td{padding:.5mm .55mm;font-size:9.25pt}
.form-page.word-cuti-form.density-2 .word-table .section-title{font-size:9.25pt}
.form-page.word-cuti-form.density-2 .word-applicant-sign{height:17mm}
.form-page.word-cuti-form.density-2 .word-decision td.word-decision-sign{height:15mm}
.form-page.word-cuti-form.density-3 .word-table{margin-bottom:1.35mm}
.form-page.word-cuti-form.density-3 .word-table td{padding:.4mm .5mm;font-size:8.9pt}
.form-page.word-cuti-form.density-3 .word-table .section-title{font-size:8.9pt}
.form-page.word-cuti-form.density-3 .word-applicant-sign{height:16mm}
.form-page.word-cuti-form.density-3 .word-decision td.word-decision-sign{height:14mm}
.form-page.word-cuti-form.density-4 .word-table{margin-bottom:1mm}
.form-page.word-cuti-form.density-4 .word-table td{padding:.3mm .4mm;font-size:8.6pt}
.form-page.word-cuti-form.density-4 .word-table .section-title{font-size:8.6pt}
.form-page.word-cuti-form.density-4 .word-applicant-sign{height:15mm}
.form-page.word-cuti-form.density-4 .word-decision td.word-decision-sign{height:13mm}
.form-page.word-cuti-form.density-5 .word-table{margin-bottom:.6mm}
.form-page.word-cuti-form.density-5 .word-table td{padding:.25mm .35mm;font-size:8.3pt}
.form-page.word-cuti-form.density-5 .word-table .section-title{font-size:8.3pt}
.form-page.word-cuti-form.density-5 .word-applicant-sign{height:13mm}
.form-page.word-cuti-form.density-5 .word-decision td.word-decision-sign{height:11mm}
/* Aturan spesifik terakhir: mencegah override CSS approval versi lama. */
.form-page.word-cuti-form .approval-table{width:100%;margin-left:0;page-break-inside:avoid}
.form-page.word-cuti-form .word-decision td.word-decision-choice{font-size:8.4pt;line-height:1.05}
.form-page.word-cuti-form.density-3 .word-decision td.word-decision-choice,
.form-page.word-cuti-form.density-4 .word-decision td.word-decision-choice,
.form-page.word-cuti-form.density-5 .word-decision td.word-decision-choice{font-size:7.7pt;line-height:1.04}

/* v1.0.12: Dua sel kosong menggantikan rowspan agar Dompdf stabil ketika
   menghitung tinggi baris pada tabel keputusan. Bentuk Word dipertahankan. */
.form-page.word-cuti-form .word-decision td.word-approval-empty{border-bottom:0}
.form-page.word-cuti-form .word-decision td.word-approval-empty-tail{border-top:0;border-bottom:.55pt solid #222}
/* Penyesuaian ruang tanda tangan hanya pada layout padat; jangan ubah @page A4. */
.form-page.word-cuti-form.density-4 .word-decision .word-sign-space{height:6mm}
.form-page.word-cuti-form.density-5 .word-decision .word-sign-space{height:5mm}
.form-page.word-cuti-form.density-5 .word-applicant-sign .word-sign-space{height:5mm}
.form-page.word-cuti-form.density-5 .form-addressee{font-size:9pt;line-height:1.0}
.form-page.word-cuti-form.density-5 .word-decision td.word-decision-position{height:4mm}
/* Pilihan fit A4 untuk Dompdf; tidak menghilangkan satu pun bagian I-VIII. */
.form-page.word-cuti-form.density-6 .form-addressee,.form-page.word-cuti-form.density-7 .form-addressee{font-size:8.5pt;line-height:1.0;margin:1mm 0 0}
.form-page.word-cuti-form.density-6 .form-caption,.form-page.word-cuti-form.density-7 .form-caption{font-size:9.2pt;margin:1mm 0}
.form-page.word-cuti-form.density-6 .header-date,.form-page.word-cuti-form.density-7 .header-date{font-size:8.5pt}
.form-page.word-cuti-form.density-6 .word-table,.form-page.word-cuti-form.density-7 .word-table{margin-bottom:.6mm}
.form-page.word-cuti-form.density-6 .word-table td{font-size:8.0pt;line-height:1.0;padding:.22mm .28mm}
.form-page.word-cuti-form.density-7 .word-table td{font-size:7.65pt;line-height:1.0;padding:.18mm .25mm}
.form-page.word-cuti-form.density-6 .word-table .section-title,.form-page.word-cuti-form.density-7 .word-table .section-title{font-size:8.0pt;line-height:1.0;padding:.20mm .25mm}
.form-page.word-cuti-form.density-7 .word-table .section-title{font-size:7.65pt}
.form-page.word-cuti-form.density-6 .word-applicant-sign,.form-page.word-cuti-form.density-7 .word-applicant-sign{height:12mm}
.form-page.word-cuti-form.density-6 .word-sign-space,.form-page.word-cuti-form.density-7 .word-sign-space{height:4mm}
.form-page.word-cuti-form.density-6 .word-decision td.word-decision-sign,.form-page.word-cuti-form.density-7 .word-decision td.word-decision-sign{font-size:7.9pt;line-height:1.0;height:10mm;vertical-align:bottom}
.form-page.word-cuti-form.density-6 .word-decision td.word-decision-choice,.form-page.word-cuti-form.density-7 .word-decision td.word-decision-choice{font-size:7.65pt;line-height:1.0;padding:.18mm .22mm}
.form-page.word-cuti-form.density-6 .word-decision td.word-decision-position,.form-page.word-cuti-form.density-7 .word-decision td.word-decision-position{font-size:7.9pt;line-height:1.0;height:3.5mm;padding:.18mm .2mm}
.form-page.word-cuti-form.density-6 .word-decision .word-sign-space,.form-page.word-cuti-form.density-7 .word-decision .word-sign-space{height:4mm}
.form-page.word-cuti-form.density-6 .word-subline,.form-page.word-cuti-form.density-7 .word-subline{font-size:7.3pt}


/* v1.0.14 - FORMULIR ACUAN Pratinjau_Formulir_Cuti_v1.0.11_A4
 * Acuan diperoleh dari PDF asli: 1 halaman A4, kolom-kolom formulir selebar bidang 160mm,
 * tulisan tabel 9.25pt, judul 11pt, tanda tangan pejabat di sisi kanan.
 * Diterapkan pada density-2 sebagai standar, level 3-7 tetap untuk kasus data panjang.
 */
.form-page.word-cuti-form.reference-v111.density-2 .word-table{margin-bottom:1.7mm}
.form-page.word-cuti-form.reference-v111.density-2 .word-table td{padding:.5mm .55mm;font-size:9.25pt;line-height:1.04}
.form-page.word-cuti-form.reference-v111.density-2 .word-table .section-title{font-size:9.25pt;line-height:1.04}
.form-page.word-cuti-form.reference-v111.density-2 .form-caption{font-size:11pt;font-weight:normal}
.form-page.word-cuti-form.reference-v111.density-2 .word-decision td.word-decision-choice{font-size:8.4pt;line-height:1.05}
.form-page.word-cuti-form.reference-v111.density-2 .word-applicant-sign{height:17mm}
.form-page.word-cuti-form.reference-v111.density-2 .word-decision td.word-decision-sign{height:15mm;vertical-align:bottom}
.form-page.word-cuti-form.reference-v111.density-2 .word-decision td.word-decision-position{height:5.5mm}
/* Struktur bagian VII-VIII: baris keputusan penuh; kolom nama pejabat tetap 52% di kanan. */
.form-page.word-cuti-form.reference-v111 .approval-table{width:100%;margin-left:0;table-layout:fixed}


/* v1.0.16: Explicit table width guides for Dompdf.
   Dompdf's fixed table layout may divide columns equally, ignoring colgroup and widths.
   Switch ONLY form tables to auto layout and anchor each column with a zero-height
   first row of explicitly sized cells before the colspan heading.
   Printable inner page width = 160mm (210 - left 30 - right 20). */
.form-page.word-cuti-form .word-table,
.form-page.word-cuti-form .approval-table,
.form-page.word-cuti-form.reference-v111 .approval-table{
    table-layout:auto!important;
}
.form-page.word-cuti-form .word-column-guide{height:0!important;max-height:0!important;line-height:0!important;font-size:0!important}
.form-page.word-cuti-form .word-column-guide td,
.form-page.word-cuti-form.density-2 .word-column-guide td,
.form-page.word-cuti-form.density-3 .word-column-guide td,
.form-page.word-cuti-form.density-4 .word-column-guide td,
.form-page.word-cuti-form.density-5 .word-column-guide td,
.form-page.word-cuti-form.density-6 .word-column-guide td,
.form-page.word-cuti-form.density-7 .word-column-guide td{
  height:0!important;min-height:0!important;max-height:0!important;
  padding:0!important;border:0!important;
  font-size:0!important;line-height:0!important;
  overflow:hidden!important;
}


/* CUTI-PDF v1.0.17: exactly three blank line-heights for all signature specimens.
 * Forms use the font-size of their current density; letters remain 12 pt.
 * Keep this block after all density rules so a compact layout cannot erase
 * the space reserved for handwritten signatures. */
.form-page.word-cuti-form .word-applicant-sign .word-sign-space{
    height:3.18em!important;
    min-height:3.18em!important;
}
.form-page.word-cuti-form .word-decision td.word-decision-sign .word-sign-space{
    height:3.15em!important;
    min-height:3.15em!important;
}
.permission-page .signature-block .sig-height,
.application-page .signature-block .sig-height{
    /* 4 x (12pt x 1.15) = 19.47mm; 20mm memberi ruang >= 4 baris. */
    height:20mm!important;
    min-height:20mm!important;
}

/* CUTI-PDF v1.0.19: formulir boleh memakai margin atas/bawah 12mm.
 * Saat bundle, hanya halaman PERTAMA yang berbeda; surat lainnya tetap sesuai tata naskah.
 * Lebar cetak tetap 160mm supaya panduan lebar kolom Dompdf tidak rusak. */
.form-page.word-cuti-form.reference-v111.density-2 .word-table td{
  font-size:10pt;line-height:1.04;
}
.form-page.word-cuti-form.reference-v111.density-2 .word-table .section-title{
  font-size:10pt;line-height:1.04;
}
.form-page.word-cuti-form.reference-v111.density-2 .word-decision td.word-decision-choice{
  font-size:8.8pt;
}
.form-page.word-cuti-form.reference-v111.density-2 .word-decision td.word-decision-position,
.form-page.word-cuti-form.reference-v111.density-2 .word-decision td.word-decision-sign{
  font-size:9.5pt;
}

/* CUTI-PDF v1.0.22: tempat nomor dipusatkan sebagai satu baris lengkap.
   Label berada di kiri area kosong 84mm, seperti nomor surat telah diisi,
   tetapi tanpa mencetak nomor atau garis. Judul tidak bergeser. */
.permission-page .letter-number{
    width:84mm!important;
    margin:1mm auto 0!important;
    padding:0!important;
    text-align:left!important;
    white-space:nowrap;
}
.permission-page .letter-number .letter-number-placeholder{
    display:inline-block;
    width:63mm;
    visibility:hidden;
}
/* Saldo N (tahun berjalan) dipusatkan, tahun N-1/N-2 tidak diubah. */
.form-page.word-cuti-form .word-history .word-current-balance{
    text-align:center!important;
}
/* Lima baris kosong untuk tanda tangan formulir pada density 2.
   Jika data panjang, Dompdf dapat memilih density berikutnya untuk
   mencegah halaman tambahan, tanpa memotong informasi. */
.form-page.word-cuti-form .word-applicant-sign .word-sign-space,
.form-page.word-cuti-form .word-decision td.word-decision-sign .word-sign-space{
    height:5.2em!important;
    min-height:5.2em!important;
}
.form-page.word-cuti-form.density-3 .word-applicant-sign .word-sign-space,
.form-page.word-cuti-form.density-3 .word-decision td.word-decision-sign .word-sign-space{
    height:5.0em!important;
    min-height:5.0em!important;
}
.form-page.word-cuti-form.density-4 .word-applicant-sign .word-sign-space,
.form-page.word-cuti-form.density-4 .word-decision td.word-decision-sign .word-sign-space{
    height:4.6em!important;
    min-height:4.6em!important;
}
.form-page.word-cuti-form.density-5 .word-applicant-sign .word-sign-space,
.form-page.word-cuti-form.density-5 .word-decision td.word-decision-sign .word-sign-space{
    height:4.2em!important;
    min-height:4.2em!important;
}
.form-page.word-cuti-form.density-6 .word-applicant-sign .word-sign-space,
.form-page.word-cuti-form.density-6 .word-decision td.word-decision-sign .word-sign-space,
.form-page.word-cuti-form.density-7 .word-applicant-sign .word-sign-space,
.form-page.word-cuti-form.density-7 .word-decision td.word-decision-sign .word-sign-space{
    height:3.6em!important;
    min-height:3.6em!important;
}


/* v1.0.24: Ukuran isi naskah halaman 2 dan 3 tetap 12pt,
   tanpa mengecilkan font saat mode density meningkat. Kop dikecualikan
   karena rasio pemerintah daerah : perangkat daerah harus 12 : 16. */
.permission-page .body-letter,
.application-page .body-letter,
.permission-page .body-letter p,
.application-page .body-letter p,
.permission-page .body-letter li,
.application-page .body-letter li,
.permission-page .letter-info td,
.application-page .letter-info td,
.permission-page .letter-title,
.permission-page .letter-number,
.application-page .application-title,
.permission-page .signature-block,
.application-page .signature-block{
    font-size:12pt!important;
}
.permission-page .signature-block,
.application-page .signature-block{
    page-break-inside:avoid;
}
</style></head><body>
@php
 $density=$density??['form'=>0,'permission'=>0,'application'=>0];
 $sup=$leave->decisions->firstWhere('stage','supervisor');
 $authority=$leave->decisions->firstWhere('stage','authority');
 $isIssued=$leave->status==='issued';
 $writtenDate=$leave->issued_at ?: ($leave->submitted_at ?: $leave->created_at);
 $workAge= !empty($employee['service_start_date']) ? \Carbon\Carbon::parse($employee['service_start_date'])->diff($writtenDate) : null;
 $balanceYears=[];
 if (!empty($employee['balance_snapshot'])) $balanceYears=$employee['balance_snapshot'];
 else {
     foreach (\App\Models\LeaveBalance::where('employee_id',$leave->employee_id)->whereBetween('year',[$leave->start_date->year-2,$leave->start_date->year])->get() as $balance) $balanceYears[$balance->year]=$balance->remaining_days;
 }
@endphp
@if(in_array($kind,['form','bundle']))@include('pdf._form')@endif
@if(in_array($kind,['permission','bundle']))@include('pdf._permission')@endif
@if(in_array($kind,['application','bundle']))@include('pdf._application')@endif
</body></html>
