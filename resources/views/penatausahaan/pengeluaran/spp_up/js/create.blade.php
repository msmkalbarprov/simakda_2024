<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.select2-multiple').select2({
            placeholder: "Silahkan Pilih",
            theme: 'bootstrap-5'
        });

        $('#rekening').on('change', function() {
            let nama = $(this).find(':selected').data('nama');
            let npwp = $(this).find(':selected').data('npwp');
            $("#nama_penerima").val(nama);
            $("#npwp").val(npwp);
        });

        $('#kode_akun').on('change', function() {
            let nama = $(this).find(':selected').data('nama');
            $("#nama_akun").val(nama);
        });

        $('#simpan_spp').on('click', function() {

        });

        function nilai(n) {
            let nilai = n.split(',').join('');
            return parseFloat(nilai) || 0;
        }

        function rupiah(n) {
            let n1 = n.split('.').join('');
            let rupiah = n1.split(',').join('.');
            return parseFloat(rupiah) || 0;
        }
    });
</script>
