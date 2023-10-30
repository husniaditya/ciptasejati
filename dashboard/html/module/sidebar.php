<!-- START Sidebar Content -->
<section class="content slimscroll">
    <!-- START Template Navigation/Menu -->
    <ul class="topmenu topmenu-responsive" data-toggle="menu">
        <li class="level1">
            <a href="dashboard.php" data-target="#dashboard" data-parent=".topmenu">
                <span class="figure"><i class="ico-home2"></i></span>
                <span class="text">Dashboard</span>
            </a>
        </li>
        <li class="level1" >
            <a href="javascript:void(0);" data-toggle="submenu" data-target="#master" data-parent=".topmenu">
                <span class="figure"><i class="ico-grid"></i></span>
                <span class="text">Master</span>
                <span class="arrow"></span>
            </a>
            <!-- START 2nd Level Menu -->
            <ul id="master" class="submenu collapse ">
                <li  class="level2">
                    <a href="tingkatgelar.php">
                        <span class="text">Tingkatan dan Gelar</span>
                    </a>
                </li>
                <li  class="level2" >
                    <a href="javascript:void(0);" data-toggle="submenu" data-target="#lokasi" data-parent="#submenu2">
                        <span class="text">Lokasi Institut</span>
                        <span class="arrow"></span>
                    </a>
                    <ul id="lokasi" class="submenu collapse ">
                        <li  class="level3" >
                            <a href="lokasipusat.php">
                                <span class="text">Pusat</span>
                            </a>
                        </li>
                        <li  class="level3" >
                            <a href="lokasidaerah.php">
                                <span class="text">Daerah</span>
                            </a>
                        </li>
                        <li class="level3" >
                            <a href="lokasicabang.php">
                                <span class="text">Cabang</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li  class="level2" >
                    <a href="idsertifikat.php">
                        <span class="text">Kartu ID dan Sertifikat</span>
                    </a>
                </li>
                <li  class="level2" >
                    <a href="dataterpusat.php">
                        <span class="text">Data Terpusat</span>
                    </a>
                </li>
            </ul>
            <!--/ END 2nd Level Menu -->
        </li>
        <li class="level1" >
            <a href="javascript:void(0);" data-toggle="submenu" data-target="#transaksi" data-parent=".topmenu">
                <span class="figure"><i class="ico-edit"></i></span>
                <span class="text">Transaksi</span>
                <span class="number"><span class="label label-success">N</span></span>
                <span class="arrow"></span>
            </a>
            <!-- START 2nd Level Menu -->
            <ul id="transaksi" class="submenu collapse ">
                <li  class="level2" >
                    <a href="kepengurusan.php">
                        <span class="text">Kepengurusan</span>
                    </a>
                </li>
                <li  class="level2" >
                    <a href="anggota.php">
                        <span class="text">Anggota</span>
                        <span class="number"><span class="label label-info">1</span></span>
                    </a>
                </li>
                <li  class="level2" >
                    <a href="javascript:void(0);" data-toggle="submenu" data-target="#aktivitas" data-parent="#submenu2">
                        <span class="text">Aktivitas</span>
                        <span class="arrow"></span>
                    </a>
                    <ul id="aktivitas" class="submenu collapse ">
                        <li class="level3" >
                            <a href="pusatdaya.php">
                                <span class="text">Pembukaan Pusat Daya</span>
                            </a>
                        </li>
                        <li class="level3" >
                            <a href="ujinaiktingkat.php">
                                <span class="text">Ujian Kenaikan Tingkat</span>
                            </a>
                        </li>
                        <li class="level3" >
                            <a href="latihangabungan.php">
                                <span class="text">Latihan Gabungan</span>
                            </a>
                        </li>
                        <li class="level3" >
                            <a href="pendidikanlatihan.php">
                                <span class="text">Pendidikan dan Latihan</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!--/ END 2nd Level Menu -->
        </li>
        <li class="level1" >
            <a href="javascript:void(0);" data-toggle="submenu" data-target="#laporan" data-parent=".topmenu">
                <span class="figure"><i class="ico-file-pdf"></i></span>
                <span class="text">Laporan</span>
                <span class="arrow"></span>
            </a>
            <!-- START 2nd Level Menu -->
            <ul id="laporan" class="submenu collapse ">
                <li  class="level2" >
                    <a href="lapdaftarcabang.php">
                        <span class="text">Daftar Cabang</span>
                    </a>
                </li>
                <li  class="level2" >
                    <a href="lapdaftarguru.php">
                        <span class="text">Daftar Dewan Guru</span>
                    </a>
                </li>
                <li  class="level2" >
                    <a href="lapdaftarpelatih.php">
                        <span class="text">Daftar Pelatih</span>
                    </a>
                </li>
                <li  class="level2" >
                    <a href="lapdaftarpengurus.php">
                        <span class="text">Daftar Pengurus</span>
                    </a>
                </li>
                <li  class="level2" >
                    <a href="lapdaftaranggota.php">
                        <span class="text">Daftar Anggota</span>
                    </a>
                </li>
                <li  class="level2" >
                    <a href="lapidanggota.php">
                        <span class="text">ID Keanggotaan</span>
                    </a>
                </li>
                <li  class="level2" >
                    <a href="lapformatstandar.php">
                        <span class="text">Format Standar</span>
                    </a>
                </li>
            </ul>
            <!--/ END 2nd Level Menu -->
        </li>
        <li class="level1">
            <a href="javascript:void(0);" data-toggle="submenu" data-target="#admin" data-parent=".topmenu">
                <span class="figure"><i class="ico-settings"></i></span>
                <span class="text">Menu Admin</span>
                <span class="number"><span class="label label-success">N</span></span>
                <span class="arrow"></span>
            </a>
            <!-- START 2nd Level Menu -->
            <ul id="admin" class="submenu collapse">
                <li  class="level2" >
                    <a href="profil.php">
                        <span class="text">Profil Institut</span>
                    </a>
                </li>
                <li  class="level2" >
                    <a href="mediasosial.php">
                        <span class="text">Media Sosial</span>
                    </a>
                </li>
                <li  class="level2" >
                    <a href="user.php">
                        <span class="text">User</span>
                    </a>
                </li>
                <li  class="level2" >
                    <a href="menu.php">
                        <span class="text">Menu</span>
                    </a>
                </li>
                <li  class="level2" >
                    <a href="javascript:void(0);" data-toggle="submenu" data-target="#manajemenkonten" data-parent="#submenu">
                        <span class="text">Manajemen Konten</span>
                        <span class="arrow"></span>
                    </a>
                    <ul id="manajemenkonten" class="submenu collapse ">
                        <li class="level3" >
                            <a href="kontenheader.php">
                                <span class="text">Header</span>
                            </a>
                        </li>
                        <li class="level3" >
                            <a href="kontenfooter.php">
                                <span class="text">Footer</span>
                            </a>
                        </li>
                        <li class="level3" >
                            <a href="javascript:void(0);" data-toggle="submenu" data-target="#beranda" data-parent="#manajemenkonten">
                                <span class="text">Halaman Beranda</span>
                                <span class="arrow"></span>
                            </a>
                            <ul id="beranda" class="submenu collapse ">
                                <li class="level4">
                                    <a href="berandaposter.php">
                                        <span class="text">Bagian Poster</span>
                                    </a>
                                </li>
                                <li class="level4" >
                                    <a href="berandakegiatan.php">
                                        <span class="text">Bagian Kegiatan</span>
                                    </a>
                                </li>
                                <li class="level4" >
                                    <a href="berandainformasi.php">
                                        <span class="text">Bagian Informasi</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="level3" >
                            <a href="kontententang.php">
                                <span class="text">Halaman Tentang Kami</span>
                            </a>
                        </li>
                        <li class="level3" >
                            <a href="javascript:void(0);" data-toggle="submenu" data-target="#cabang" data-parent="#manajemenkonten">
                                <span class="text">Halaman Cabang</span>
                                <span class="arrow"></span>
                            </a>
                            <ul id="cabang" class="submenu collapse ">
                                <li class="level4" >
                                    <a href="daftarcabang.php">
                                        <span class="text">Daftar Cabang</span>
                                    </a>
                                </li>
                                <li class="level4" >
                                    <a href="koordinatorcabang.php">
                                        <span class="text">Koordinator Cabang</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="level3" >
                            <a href="kontenblog.php">
                                <span class="text">Halaman Blog</span>
                            </a>
                        </li>
                        <li class="level3" >
                            <a href="kontenhubungi.php">
                                <span class="text">Halaman Hubungi</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!--/ END 2nd Level Menu -->
        </li>
            </ul>
            <!--/ END 2nd Level Menu -->
        </li>
    </ul>
    <!--/ END Template Navigation/Menu -->
</section>
<!--/ END Sidebar Container -->