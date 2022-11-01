<div data-simplebar class="sidebar-menu-scroll">

    <!--- Sidemenu -->
    <div id="sidebar-menu">
        <!-- Left Menu Start -->
        <ul class="metismenu list-unstyled" id="side-menu">
            <li class="menu-title">Menu</li>

            <li>
                <a href="{{ route('home') }}">
                    <i class="uil-home-alt"></i><span class="badge rounded-pill bg-primary float-end"></span>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="uil-window-section"></i>
                    <span>Kelola Akses</span>
                </a>
                <ul class="sub-menu" aria-expanded="true">
                    <li><a href="{{ route('user.index') }}">Pengguna</a></li>
                    <li><a href="{{ route('peran.index') }}">Peran</a></li>
                    <li><a href="{{ route('hak-akses.index') }}">Hak Akses</a></li>
                </ul>
            </li>
            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="uil-window-section"></i>
                    <span>Master</span>
                </a>
                <ul class="sub-menu" aria-expanded="true">
                    <li><a href="{{ route('penerima.index') }}">Penerima</a></li>
                    <li><a href="{{ route('kontrak.index') }}">Kontrak</a></li>
                </ul>
            </li>
            {{-- Pengeluaran --}}
            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="uil-window-section"></i>
                    <span>Penatausahaan</span>
                </a>
                <ul class="sub-menu" aria-expanded="true">
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">Pengeluaran</a>
                        <ul class="sub-menu" aria-expanded="true">
                            <li><a href="{{ route('penagihan.index') }}">Penagihan</a></li>
                            <li><a href="{{ route('sppls.index') }}">SPP LS</a></li>
                            <li><a href="{{ route('spm.index') }}">SPM</a></li>
                            <li><a href="{{ route('sp2d.index') }}">SP2D</a></li>
                            <li><a href="{{ route('daftar_penguji.index') }}">Daftar Penguji</a></li>
                            <li><a href="{{ route('pencairan_sp2d.index') }}">Pencairan SP2D</a></li>
                            <li><a href="{{ route('sppup.index') }}">SPP UP</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            {{-- Transaksi Bendahara --}}
            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="uil-window-section"></i>
                    <span>Transaksi Bendahara</span>
                </a>
                <ul class="sub-menu" aria-expanded="true">
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">SP2D</a>
                        <ul class="sub-menu" aria-expanded="true">
                            <li><a href="{{ route('terima_sp2d.index') }}">Terima SP2D</a></li>
                            <li><a href="{{ route('skpd.pencairan_sp2d.index') }}">Pencairan SP2D</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">Transaksi</a>
                        <ul class="sub-menu" aria-expanded="true">
                            <li><a href="{{ route('skpd.transaksi_cms.index') }}">Transaksi CMS</a></li>
                            <li><a href="{{ route('skpd.upload_cms.index') }}">Upload Transaksi CMS</a></li>
                            <li><a href="{{ route('skpd.validasi_cms.index') }}">Validasi Transaksi CMS</a></li>
                            <li><a href="{{ route('skpd.potongan_pajak_cms.index') }}">Terima Potongan Pajak (CMS)</a>
                            </li>
                            <li><a href="{{ route('skpd.transaksi_tunai.index') }}">Transaksi Tunai</a></li>
                            <li><a href="{{ route('skpd.transaksi_pemindahbukuan.index') }}">Transaksi
                                    Pemindahbukuan</a></li>
                            <li><a href="{{ route('skpd.potongan_pajak.index') }}">Terima Potongan Pajak</a>
                            <li><a href="{{ route('skpd.potongan_pajak.index') }}">Setor Potongan Pajak</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            {{-- @php
                $daftar_menu = filter_menu();
                $daftar_menu1 = daftar_menu();
            @endphp
            @foreach ($daftar_menu as $menu)
                @if ($menu->urutan_menu == '1')
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="uil-window-section"></i>
                            <span>{{ $menu->display_name }}</span>
                        </a>
                        @foreach ($daftar_menu1 as $sub)
                            @php
                                $menu1 = $sub->name;
                            @endphp
                            @if ($sub->parent_id == $menu->id)
                                <ul class="sub-menu" aria-expanded="true">
                                    <li>
                                        <a href="{{ route($menu1) }}">{{ $sub->display_name }}</a>
                                    </li>
                                </ul>
                            @endif
                        @endforeach
                    </li>
                @else
                @endif
            @endforeach --}}
        </ul>
    </div>
    <!-- Sidebar -->
</div>
