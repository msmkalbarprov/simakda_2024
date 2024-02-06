@if ($beban == '1')
    <tr>
        <td class="a1">a. SPM-UP</td>
        <td style="border: 1px solid black;width:10%"></td>
        <td style="border: 1px solid black;width:10%"></td>
    </tr>
    <tr>
        <td class="a1">b. Surat Pernyataan Tanggung Jawab Mutlak (SPTJM)</td>
        <td style="border: 1px solid black;width:10%"></td>
        <td style="border: 1px solid black;width:10%"></td>
    </tr>
    <tr>
        <td class="a1">c. Surat Pernyataan Verifikassi PPK SKPD dan lembar <i>checklist</i> kelengkapan dokumen</td>
        <td style="border: 1px solid black;width:10%"></td>
        <td style="border: 1px solid black;width:10%"></td>
    </tr>
    <tr>
        <td class="a1">d. Dokumen lain yang diperlukan</td>
        <td style="border: 1px solid black;width:10%"></td>
        <td style="border: 1px solid black;width:10%"></td>
    </tr>
@elseif ($beban == '2')
    <tr>
        <td class="a1">a. SPM-GU</td>
        <td style="border: 1px solid black;width:10%"></td>
        <td style="border: 1px solid black;width:10%"></td>
    </tr>
    <tr>
        <td class="a1">b. Surat Pernyataan Tanggungjawab Mutlak (SPTJM)</td>
        <td style="border: 1px solid black;width:10%"></td>
        <td style="border: 1px solid black;width:10%"></td>
    </tr>
    <tr>
        <td class="a1">c. Surat Pernyataan Verifikasi PPK SKPD dan lembar <i>checklist</i> kelengkapan dokumen</td>
        <td style="border: 1px solid black;width:10%"></td>
        <td style="border: 1px solid black;width:10%"></td>
    </tr>
    <tr>
        <td class="a1">d. Surat Pernyataan Tanggung Jawab Belanja (SPTB)</td>
        <td style="border: 1px solid black;width:10%"></td>
        <td style="border: 1px solid black;width:10%"></td>
    </tr>
    <tr>
        <td class="a1">e. Rekap LPJ UP</td>
        <td style="border: 1px solid black;width:10%"></td>
        <td style="border: 1px solid black;width:10%"></td>
    </tr>
    <tr>
        <td class="a1">f. Dokumen lain yang diperlukan</td>
        <td style="border: 1px solid black;width:10%"></td>
        <td style="border: 1px solid black;width:10%"></td>
    </tr>
@elseif ($beban == '3')
    <tr>
        <td class="a1">a. SPM-TU</td>
        <td style="border: 1px solid black;width:10%"></td>
        <td style="border: 1px solid black;width:10%"></td>
    </tr>
    <tr>
        <td class="a1">b. Surat Pernyataan Tanggungjawab Mutlak (SPTJM)</td>
        <td style="border: 1px solid black;width:10%"></td>
        <td style="border: 1px solid black;width:10%"></td>
    </tr>
    <tr>
        <td class="a1">c. Surat Pernyataan Verifikasi PPK SKPD dan lembar <i>checklist</i> kelengkapan dokumen</td>
        <td style="border: 1px solid black;width:10%"></td>
        <td style="border: 1px solid black;width:10%"></td>
    </tr>
    <tr>
        <td class="a1">d. Pengesahan LPJ-TU oleh kuasa BUD</td>
        <td style="border: 1px solid black;width:10%"></td>
        <td style="border: 1px solid black;width:10%"></td>
    </tr>
    <tr>
        <td class="a1">e. Dokumen lain yang diperlukan</td>
        <td style="border: 1px solid black;width:10%"></td>
        <td style="border: 1px solid black;width:10%"></td>
    </tr>
@elseif (in_array($beban, ['5', '6']))
    @if ($beban == '6' && ($jenis == '5' || $jenis == '6'))
        <tr>
            <td class="a1">a. SPM-LS Barang dan Jasa</td>
            <td style="border: 1px solid black;width:10%"></td>
            <td style="border: 1px solid black;width:10%"></td>
        </tr>
        <tr>
            <td class="a1">b. Surat Pernyataan Tanggungjawab Mutlak (SPTJM)</td>
            <td style="border: 1px solid black;width:10%"></td>
            <td style="border: 1px solid black;width:10%"></td>
        </tr>
        <tr>
            <td class="a1">c. Surat Pernyataan Verifikassi PPK SKPD dan lembar <i>checklist</i> kelengkapan dokumen
            </td>
            <td style="border: 1px solid black;width:10%"></td>
            <td style="border: 1px solid black;width:10%"></td>
        </tr>
        <tr>
            <td class="a1">d. Ringkasan Kontrak
            </td>
            <td style="border: 1px solid black;width:10%"></td>
            <td style="border: 1px solid black;width:10%"></td>
        </tr>
        <tr>
            <td class="a1">e. Referensi/Fotocopy Rekening Bank
            </td>
            <td style="border: 1px solid black;width:10%"></td>
            <td style="border: 1px solid black;width:10%"></td>
        </tr>
        <tr>
            <td class="a1">f. Foto Copy NPWP
            </td>
            <td style="border: 1px solid black;width:10%"></td>
            <td style="border: 1px solid black;width:10%"></td>
        </tr>
        <tr>
            <td class="a1">g. Jaminan Uang Muka
            </td>
            <td style="border: 1px solid black;width:10%"></td>
            <td style="border: 1px solid black;width:10%"></td>
        </tr>
        <tr>
            <td class="a1">h. Jaminan Pemeliharaan
            </td>
            <td style="border: 1px solid black;width:10%"></td>
            <td style="border: 1px solid black;width:10%"></td>
        </tr>
        <tr>
            <td class="a1">i. Berita Acara Pembiayaan
            </td>
            <td style="border: 1px solid black;width:10%"></td>
            <td style="border: 1px solid black;width:10%"></td>
        </tr>
        <tr>
            <td class="a1">j. Faktur Pajak
            </td>
            <td style="border: 1px solid black;width:10%"></td>
            <td style="border: 1px solid black;width:10%"></td>
        </tr>
        <tr>
            <td class="a1">k. <i>e</i>-Billing (PPn dan PPh)
            </td>
            <td style="border: 1px solid black;width:10%"></td>
            <td style="border: 1px solid black;width:10%"></td>
        </tr>
        <tr>
            <td class="a1">l. Dokumen lain yang diperlukan
            </td>
            <td style="border: 1px solid black;width:10%"></td>
            <td style="border: 1px solid black;width:10%"></td>
        </tr>
    @else
        <tr>
            <td class="a1">a. SPM-LS</td>
            <td style="border: 1px solid black;width:10%"></td>
            <td style="border: 1px solid black;width:10%"></td>
        </tr>
        <tr>
            <td class="a1">b. Ringkasan SPM-LS</td>
            <td style="border: 1px solid black;width:10%"></td>
            <td style="border: 1px solid black;width:10%"></td>
        </tr>
        <tr>
            <td class="a1">c. Lampiran SPM Gaji LS</td>
            <td style="border: 1px solid black;width:10%"></td>
            <td style="border: 1px solid black;width:10%"></td>
        </tr>
        <tr>
            <td class="a2">- Laporan Penelitian Kelengkapan Dokumen Penerbitan</td>
            <td style="border: 1px solid black;width:10%"></td>
            <td style="border: 1px solid black;width:10%"></td>
        </tr>
        <tr>
            <td class="a2">- Daftar tanda terima pembayaran Honor yang sudah ditandatangan (Tanda Tangan pembuat
                daftar,
                mengetahui PPTK dan setuju dibayar PA/KPA)</td>
            <td style="border: 1px solid black;width:10%"></td>
            <td style="border: 1px solid black;width:10%"></td>
        </tr>
        <tr>
            <td class="a2">- Surat Setoran Elektronik (SSE BPJS 2 % dan 3 %</td>
            <td style="border: 1px solid black;width:10%"></td>
            <td style="border: 1px solid black;width:10%"></td>
        </tr>
        <tr>
            <td class="a2">- Surat Pernyataan Tanggungjawab Mutlak</td>
            <td style="border: 1px solid black;width:10%"></td>
            <td style="border: 1px solid black;width:10%"></td>
        </tr>
        <tr>
            <td class="a2">- Syarat-syarat lainnya sesuai ketentuan yang berlaku</td>
            <td style="border: 1px solid black;width:10%"></td>
            <td style="border: 1px solid black;width:10%"></td>
        </tr>
    @endif
@elseif ($beban == '4')
    <tr>
        <td class="a1">a. SPM-LS Gaji dan Tunjangan</td>
        <td style="border: 1px solid black;width:10%"></td>
        <td style="border: 1px solid black;width:10%"></td>
    </tr>
    <tr>
        <td class="a1">b. Surat Pernyataan Tanggung Jawab Mutlak (SPTJM)</td>
        <td style="border: 1px solid black;width:10%"></td>
        <td style="border: 1px solid black;width:10%"></td>
    </tr>
    <tr>
        <td class="a1">c. Surat Pernyataan Verifikasi PPK SKPD dan lembar <i>checklist</i> kelengkapan dokumen</td>
        <td style="border: 1px solid black;width:10%"></td>
        <td style="border: 1px solid black;width:10%"></td>
    </tr>
    <tr>
        <td class="a1">d. Rekapitulasi gaji induk/gaji terusan/kekurangan gaji/gaji susulan (sesuai yang diminta)
        </td>
        <td style="border: 1px solid black;width:10%"></td>
        <td style="border: 1px solid black;width:10%"></td>
    </tr>
    <tr>
        <td class="a1">e. Iuran BPJS Kesehatan (4%) dan Iuran Wajib Pegawai (1%)
        </td>
        <td style="border: 1px solid black;width:10%"></td>
        <td style="border: 1px solid black;width:10%"></td>
    </tr>
    <tr>
        <td class="a1">f. Bukti Setoran JKK dan JKM
        </td>
        <td style="border: 1px solid black;width:10%"></td>
        <td style="border: 1px solid black;width:10%"></td>
    </tr>
    <tr>
        <td class="a1">g. <i>e</i>-Billing Pajak
        </td>
        <td style="border: 1px solid black;width:10%"></td>
        <td style="border: 1px solid black;width:10%"></td>
    </tr>
    <tr>
        <td class="a1">h. Dokumen lain yang diperlukan
        </td>
        <td style="border: 1px solid black;width:10%"></td>
        <td style="border: 1px solid black;width:10%"></td>
    </tr>
@endif
