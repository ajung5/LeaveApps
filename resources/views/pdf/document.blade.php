<!doctype html><html lang="id"><head><meta charset="utf-8"><title>{{ $kind }} {{ $leave->request_number }}</title><style>
@page{size:A4 portrait;margin:9mm 11mm 9mm 11mm}
*{box-sizing:border-box}body{font-family:"DejaVu Sans",sans-serif;font-size:8pt;line-height:1.29;color:#111;margin:0}
h1,h2,p{margin:0}table{border-collapse:collapse;width:100%;table-layout:fixed}td,th{vertical-align:top;word-wrap:break-word}
.page{width:100%;position:relative}.page + .page{page-break-before:always}
.center{text-align:center}.right{text-align:right}.bold{font-weight:bold}.upper{text-transform:uppercase}.small{font-size:7pt}.tiny{font-size:6.4pt}.space4{height:4mm}.space3{height:3mm}.space2{height:2mm}.rule{height:1px;background:#222;margin:3mm 0}.form-table td,.form-table th{border:.6px solid #333;padding:2mm 1.7mm}.form-table.tight td{padding:1.25mm 1.5mm}.form-table .section-title{font-weight:bold;font-size:7.6pt;background:#f8f8f8;padding:1.4mm 1.5mm}.checkbox{display:inline-block;border:.8px solid #111;width:3.2mm;height:3.2mm;text-align:center;line-height:3.1mm;font-size:7pt;margin-left:1.6mm}.signature-cell{height:24mm;text-align:center}.signature-name{font-weight:bold;text-decoration:underline}.form-caption{font-size:11.1pt;font-family:"Times New Roman",serif;text-align:center;font-weight:bold;margin:3mm 0 5mm}.form-opening{height:38mm}.address-cell{height:23mm}.sign-info{padding-top:2mm;text-align:center}.sign-space{height:15mm}.header-date{text-align:right;padding-right:8mm}.letterhead{border-bottom:2.4pt solid #111;height:29mm;padding-bottom:1mm;margin-bottom:7mm}.logo{float:left;width:18mm;height:20mm;object-fit:contain;margin:0 5mm 0 3mm}.letterhead-text{text-align:center;padding-top:1mm}.letterhead-text .gov{font-size:12pt;font-weight:normal}.letterhead-text .agency{font-size:16pt;font-weight:bold}.letterhead-text .addr{font-size:8pt}.letter-title{font-size:11pt;font-weight:bold;text-decoration:underline;text-align:center}.letter-number{font-size:8.5pt;text-align:center;margin-top:1mm}.letter-info td{padding:1mm .8mm;font-size:9pt}.body-letter{font-size:9pt;line-height:1.55}.body-letter p{margin:0 0 4mm}.body-letter ol{margin:0 0 4mm 6mm;padding-left:5mm}.body-letter li{padding-left:1.5mm}.signature-block{width:48%;margin-left:52%;font-size:8.6pt}.sig-height{height:26mm}.draft-note{position:fixed;bottom:2mm;right:0;left:0;text-align:center;color:#b32130;font-weight:bold;font-size:7pt}.letterdate{font-size:7.5pt}.docid{font-size:6.5pt;color:#555;text-align:right}.highlight{background:#f1f1f1}.no-border td{border:none}.leading{line-height:1.55}.margin-top{margin-top:5mm}.permit-title-area{margin:9mm 0 7mm}.text-indent{text-indent:6mm}

/* v1.0.2: kepadatan per surat, tidak memotong isi halaman. */
.form-page .form-caption{font-size:10pt;margin:2.2mm 0 3mm}
.form-page .space3{height:1.4mm}
.form-page .form-table.tight td{padding:.75mm 1.2mm;line-height:1.17}
.form-page .form-table .section-title{font-size:7.4pt;padding:.9mm 1.2mm}
.form-page .signature-cell{height:18mm}
.form-page .address-cell{height:17mm}
.form-page .sign-space{height:9mm}
.form-page .checkbox{width:2.8mm;height:2.8mm;line-height:2.7mm}
.form-page .docid{margin-top:1mm}
.form-page.density-1{font-size:7.6pt;line-height:1.16}
.form-page.density-1 .form-table.tight td{padding:.6mm 1mm}
.form-page.density-1 .form-table .section-title{padding:.7mm 1mm}
.form-page.density-1 .space3{height:1mm}
.form-page.density-1 .signature-cell{height:15mm}
.form-page.density-1 .address-cell{height:15mm}
.form-page.density-1 .sign-space{height:7mm}
.form-page.density-2{font-size:7.2pt;line-height:1.14}
.form-page.density-2 .form-table.tight td{padding:.45mm .85mm}
.form-page.density-2 .form-table .section-title{font-size:7pt;padding:.5mm .85mm}
.form-page.density-2 .space3{height:.6mm}
.form-page.density-2 .signature-cell{height:13mm}
.form-page.density-2 .address-cell{height:13mm}
.form-page.density-2 .sign-space{height:6mm}
.form-page.density-2 .form-caption{font-size:9.4pt;margin:1.5mm 0 2mm}
.permission-page.density-1 .letterhead{height:26mm;margin-bottom:5mm}
.permission-page.density-1 .permit-title-area{margin:6mm 0 5mm}
.permission-page.density-1 .body-letter{font-size:8.5pt;line-height:1.35}
.permission-page.density-1 .body-letter p{margin-bottom:2.5mm}
.permission-page.density-1 .body-letter ol{margin-bottom:2.5mm}
.permission-page.density-1 .sig-height{height:20mm}
.permission-page.density-2 .letterhead{height:24mm;margin-bottom:3mm}
.permission-page.density-2 .permit-title-area{margin:4mm 0 3mm}
.permission-page.density-2 .body-letter{font-size:8pt;line-height:1.25}
.permission-page.density-2 .body-letter p{margin-bottom:1.5mm}
.permission-page.density-2 .body-letter ol{margin-bottom:1.5mm}
.permission-page.density-2 .letter-info td{font-size:8pt;padding:.5mm .7mm}
.permission-page.density-2 .sig-height{height:16mm}
.application-page.density-1 .application-title{margin:9mm 0 7mm !important}
.application-page.density-1 .application-body{font-size:9pt !important;line-height:1.35}
.application-page.density-1 .application-signature{margin-top:16mm !important}
.application-page.density-1 .application-signature .sig-height{height:21mm}
.application-page.density-1 .application-docid{margin-top:10mm !important}
.application-page.density-2 .application-title{margin:6mm 0 5mm !important}
.application-page.density-2 .application-body{font-size:8.5pt !important;line-height:1.25}
.application-page.density-2 .application-body p{margin-bottom:2mm}
.application-page.density-2 .application-body .letter-info td{font-size:8pt;padding:.5mm .7mm}
.application-page.density-2 .application-signature{margin-top:10mm !important}
.application-page.density-2 .application-signature .sig-height{height:17mm}
.application-page.density-2 .application-docid{margin-top:6mm !important}
/* v1.0.5: kop resmi 3:4 (12 pt dan 16 pt). Arial memakai font lokal jika tersedia. */
@if(!empty($pdfArialRegular) && !empty($pdfArialBold))
@font-face{font-family:'CutiArial';src:url('data:font/truetype;base64,{{ $pdfArialRegular }}') format('truetype');font-weight:400;font-style:normal}
@font-face{font-family:'CutiArial';src:url('data:font/truetype;base64,{{ $pdfArialBold }}') format('truetype');font-weight:700;font-style:normal}
@endif
.permission-page .letterhead-text{font-family:'CutiArial',Arial,Helvetica,sans-serif}
/* Dua blok persetujuan sejajar dengan batas kiri kolom tanda tangan pemohon (53%). */
.form-page .approval-table{width:47%;margin-left:53%;table-layout:fixed;page-break-inside:avoid}
.form-page .approval-table .section-title{font-size:6.75pt;line-height:1.22;padding:.9mm 1mm}
.form-page .approval-table .approval-choice{text-align:left;font-size:6.4pt;line-height:1.25;padding:.65mm 1mm;white-space:nowrap}
.form-page .approval-table .checkbox{width:2.6mm;height:2.6mm;line-height:2.5mm;margin-left:.7mm;vertical-align:middle}
.form-page .approval-table .approval-signature{font-size:7.2pt;line-height:1.2;height:17mm;text-align:center;white-space:normal}
.form-page .approval-table .approval-signature .sign-space{height:8mm}
.form-page.density-1 .approval-table .approval-signature{height:15mm}
.form-page.density-1 .approval-table .approval-signature .sign-space{height:6.5mm}
.form-page.density-2 .approval-table .approval-signature{height:13.5mm}
.form-page.density-2 .approval-table .approval-signature .sign-space{height:5mm}
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
