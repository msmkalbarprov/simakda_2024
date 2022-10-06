<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#transaksi_cms').DataTable();

        $('#cetak_cms').on('click', function() {
            let tgl_voucher = document.getElementById('tgl_voucher').value;
            if (!tgl_voucher) {
                alert('Tanggal tidak boleh kosong!');
                return;
            }
            let url = new URL("{{ route('skpd.transaksi_cms.cetak_list') }}");
            let searchParams = url.searchParams;
            searchParams.append("tgl_voucher", tgl_voucher);
            window.open(url.toString(), "_blank");
        });

    });

    function deleteData(no_voucher) {
        let tanya = confirm('Apakah anda yakin untuk menghapus dengan Nomor Bukti : ' + no_voucher)
        if (tanya == true) {
            $.ajax({
                url: "{{ route('skpd.transaksi_cms.hapus_cms') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    no_voucher: no_voucher
                },
                success: function(data) {
                    if (data.message == '1') {
                        alert('Data berhasil dihapus!');
                        location.reload();
                    } else {
                        alert('Data gagal dihapus!');
                        return;
                    }
                }
            })
        } else {
            return false;
        }
    }
</script>
