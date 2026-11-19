<!doctype html><html lang="id"><head><meta charset="utf-8"><title>{{ $kind }} {{ $leave->request_number }}</title><style>
@if(!empty($pdfBookmanRegular))
@font-face{font-family:'CutiBookman';src:url('data:font/truetype;base64,{{ $pdfBookmanRegular }}') format('truetype');font-weight:400;font-style:normal}
@endif
@if(!empty($pdfBookmanBold))
@font-face{font-family:'CutiBookman';src:url('data:font/truetype;base64,{{ $pdfBookmanBold }}') format('truetype');font-weight:700;font-style:normal}
@endif
@page{size:A4 portrait;margin:20mm 20mm 20mm 30mm}
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
.letterhead{border-bottom:2pt solid #111;margin-bottom:8.5mm;padding-bottom:1mm;min-height:27mm}
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
