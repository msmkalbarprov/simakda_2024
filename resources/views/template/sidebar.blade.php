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
                        </ul>
                    </li>
                </ul>
            </li>

        </ul>
    </div>
    <!-- Sidebar -->
</div>
