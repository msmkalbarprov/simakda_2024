<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#cair_sp2d').DataTable();

    });

    function cetak(no_sp2d, beban, kd_skpd) {
        $('#no_sp2d').val(no_sp2d);
        $('#beban').val(beban);
        $('#kd_skpd').val(kd_skpd);
        if (beban == '4') {
            $('#lampiran_lama').show();
        } else {
            $('#lampiran_lama').hide();
        }
        $('#modal_cetak').modal('show');
    }

    function batal_sp2d(no_sp2d, beban, kd_skpd, no_spm, no_spp, status) {
        $('#no_sp2d_batal').val(no_sp2d);
        $('#beban_batal').val(beban);
        $('#no_spm_batal').val(no_spm);
        $('#no_spp_batal').val(no_spp);
        $('#status_bud').val(status);
        $('#sp2d_batal').modal('show');
    }

    function deleteData(no_spp) {
        let tanya = confirm('Apakah anda yakin untuk menghapus dengan Nomor SPP : ' + no_spp)
        if (tanya == true) {
            $.ajax({
                url: "{{ route('sppls.hapus_sppls') }}",
                type: "DELETE",
                dataType: 'json',
                data: {
                    no_spp: no_spp
                },
                success: function(data) {
                    if (data.message == '1') {
                        alert('Data berhasil dihapus!');
                        location.reload();
                    } else {
                        alert('Data gagal dihapus!');
                        location.reload();
                    }
                }
            })
        } else {
            return false;
        }
    }
</script>
