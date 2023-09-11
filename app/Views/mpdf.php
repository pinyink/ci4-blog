<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        @page page {
            margin-left: 20mm;
            margin-top: 10mm;
            margin-right: 15mm;
            margin-bottom: 15mm;
        }

        div.page {
            page: page;
        }

        .logo {
            width: 20mm;
            height: 28mm;
        }

        .line {
            border-bottom: 4px solid #333;
            border-top: 2px solid #999;
            padding-top: 1px;
        }

        .text {
            font-family: times;
            font-size: 11pt;
            line-height: 1.6;
        }

        .center {
            text-align: center;
        }

        .justify {
            text-align: justify;
        }

        .table tr td {
            padding: 5px;
        }
    </style>
</head>
<body>
    <div class="page">
        <div style="margin-bottom: 5px;">
            <div style="float: left; width: 20mm;">
                <img src="<?=PUBLICPATH.'/assets/img/logo-pemkab-jepara-hitam-putih.png'?>" alt="" class="logo">
            </div>
            <div style="float: right; width: 160mm; text-align: center; padding-top: 5px;">
                <div style="font-size: 14pt; font-weight: bold">
                    PEMERINTAH KABUPATEN JEPARA
                </div>
                <div style="font-size: 18pt;">
                    <b>DINAS PEKERJAAN UMUM DAN PENATAAN RUANG</b>
                </div>
                <div style="font-size: 12pt;">
                    Jalan Kartini Nomor 27 Telp/Fax. (0291) 591032 <br>
                    Website : http://dpupr.jepara.go.id Kode Pos 59417
                </div>
            </div>
        </div>
        <div class="line"></div>
        <div style="margin-top: 6mm; font-family: times; font-size: 14pt; font-weight: bold; text-align: center">
            <u>SURAT KETERANGAN POLA RUANG LOKASI USAHA PADA <br>RENCANA TATA RUANG KABUPATEN JEPARA</u>
        </div>
        <div class="text center">
            Nomor: 
        </div>
        <div style="margin-top: 5mm" class="text">
            Perihal Permohonan Validasi KKPR, dengan ini menerangkan informasi sebagai berikut:
        </div>
        <table class="table text" style="width: 100%; margin-top: 5mm">
            <tr>
                <td style="width: 35%;">Nama</td>
                <td style="width: 5%;">:</td>
                <td style="width: 60%;"></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td></td>
            </tr>
            <tr>
                <td>Lokasi Kegiatan</td>
                <td>:</td>
                <td></td>
            </tr>
            <tr>
                <td>Kegiatan Usaha</td>
                <td>:</td>
                <td></td>
            </tr>
            <tr>
                <td>Nama Usaha</td>
                <td>:</td>
                <td></td>
            </tr>
            <tr>
                <td>Bukti Kepemilikan Tanah</td>
                <td>:</td>
                <td></td>
            </tr>
            <tr>
                <td>Bukti Kepemilikan KKPR</td>
                <td>:</td>
                <td></td>
            </tr>
            <tr>
                <td>Koordinat (by sistem)</td>
                <td>:</td>
                <td></td>
            </tr>
            <tr>
                <td>Pola Ruang (By Sistem)</td>
                <td>:</td>
                <td></td>
            </tr>
            <tr>
                <td>Ketentuan Khusus</td>
                <td>:</td>
                <td></td>
            </tr>
        </table>
        <div style="margin-top: 5mm;"></div>
        <div class="text justify" style="margin-bottom: 2mm; text-indent: 50px;">
            Surat Keterangan ini bersifat memberikan informasi dan bukan merupakan dasar izin untuk melakukan aktivitas/operasional.
        </div>
        <div class="text justify" style="text-indent: 50px;">
            Demikian Surat Keterangan ini dibuat, apabila dikemudian hari terdapat kekeliruan dalam Surat Keterangan ini, akan diadakan perbaikan sebagaimana mestinya.
        </div>
    </div>
</body>
</html>