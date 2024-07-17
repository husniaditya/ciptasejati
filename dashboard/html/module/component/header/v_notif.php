

<div id="ViewNotifMutasi" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="ViewNotifMutasi-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Lihat Data Mutasi Anggota</h3>
                </div>
                <div class="modal-body">
                    <h5 class="text-center" id="viewnotifMUTASI_STATUS_DES"></h5><br>
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <div class="form-group">
                                <label>Diajukan Oleh</label>
                                <p id="viewnotifINPUT_BY"></p>
                            </div> 
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <div class="form-group">
                                <label>Tanggal Pengajuan</label>
                                <p id="viewnotifINPUT_DATE"></p>
                            </div> 
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <div class="form-group">
                                <label>Disetujui Oleh</label>
                                <p id="viewnotifAPPROVE_BY"></p>
                            </div> 
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <div class="form-group">
                                <label>Tanggal persetujuan</label>
                                <p id="viewnotifMUTASI_APP_TANGGAL"></p>
                            </div> 
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" align="center">
                            <div class="short-div">
                                <label>Foto Anggota </label><br>
                                <div id="notifloadpicview"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="short-div">
                                <div class="form-group">
                                    <label>ID - Nama Anggota</label>
                                    <input type="text" class="form-control" id="viewnotifANGGOTA_IDNAMA" name="ANGGOTA_ID" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div hidden">
                                <div class="form-group">
                                    <label>ID Cabang</label>
                                    <input type="text" class="form-control" id="viewnotifCABANG_AWAL" name="CABANG_AWAL" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Daerah</label>
                                    <input type="text" class="form-control" id="viewnotifDAERAH_AWAL_DES" name="DAERAH_AWAL" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Cabang</label>
                                    <input type="text" class="form-control" id="viewnotifCABANG_AWAL_DES" name="CABANG_DESKRIPSI" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Sabuk</label>
                                    <input type="text" class="form-control" id="viewnotifTINGKATAN_NAMA" name="TINGKATAN_ID" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Tingkatan</label>
                                    <input type="text" class="form-control" id="viewnotifTINGKATAN_SEBUTAN" name="TINGKATAN_SEBUTAN" value="" readonly>
                                </div> 
                            </div>   
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Daerah Tujuan</label>
                                <input type="text" class="form-control" id="viewnotifDAERAH_TUJUAN_DES" name="DAERAH_TUJUAN" value="" readonly>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cabang Tujuan</label>
                                <input type="text" class="form-control" id="viewnotifCABANG_TUJUAN_DES" name="CABANG_TUJUAN" value="" readonly>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea type="text" rows="4" class="form-control" id="viewnotifMUTASI_DESKRIPSI" name="MUTASI_DESKRIPSI" value="" data-parsley-required readonly></textarea>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Efektif</label>
                                <input type="text" class="form-control" id="viewnotifTANGGAL_EFEKTIF" name="MUTAS_TANGGAL" value="" readonly>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Close</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="ApproveNotifMutasi" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="ApproveNotifMutasi-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Persetujuan Data Mutasi Anggota</h3>
                </div>
                <div class="modal-body">
                    <h5 class="text-center" id="appnotifMUTASI_STATUS_DES"></h5><br>
                    <div class="row hidden">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>ID Mutasi</label>
                                <input type="text" class="form-control" id="appnotifMUTASI_ID" name="MUTASI_ID" value="" readonly>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <div class="form-group">
                                <label>Diajukan Oleh</label>
                                <p id="appnotifINPUT_BY"></p>
                            </div> 
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <div class="form-group">
                                <label>Tanggal Pengajuan</label>
                                <p id="appnotifINPUT_DATE"></p>
                            </div> 
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <div class="form-group">
                                <label>Disetujui Oleh</label>
                                <p id="appnotifAPPROVE_BY"></p>
                            </div> 
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <div class="form-group">
                                <label>Tanggal persetujuan</label>
                                <p id="appnotifMUTASI_APP_TANGGAL"></p>
                            </div> 
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" align="center">
                            <div class="short-div">
                                <label>Foto Anggota </label><br>
                                <div id="notifloadpicapp"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="short-div">
                                <div class="form-group">
                                    <label>ID - Nama Anggota</label>
                                    <input type="text" class="form-control" id="appnotifANGGOTA_IDNAMA" name="ANGGOTA_ID" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div hidden">
                                <div class="form-group">
                                    <label>ID Cabang</label>
                                    <input type="text" class="form-control" id="appnotifCABANG_AWAL" name="CABANG_AWAL" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Daerah</label>
                                    <input type="text" class="form-control" id="appnotifDAERAH_AWAL_DES" name="DAERAH_AWAL" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Cabang</label>
                                    <input type="text" class="form-control" id="appnotifCABANG_AWAL_DES" name="CABANG_DESKRIPSI" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Sabuk</label>
                                    <input type="text" class="form-control" id="appnotifTINGKATAN_NAMA" name="TINGKATAN_ID" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Tingkatan</label>
                                    <input type="text" class="form-control" id="appnotifTINGKATAN_SEBUTAN" name="TINGKATAN_SEBUTAN" value="" readonly>
                                </div> 
                            </div>   
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Daerah Tujuan</label>
                                <input type="text" class="form-control" id="appnotifDAERAH_TUJUAN_DES" name="DAERAH_TUJUAN" value="" readonly>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cabang Tujuan</label>
                                <input type="text" class="form-control" id="appnotifCABANG_TUJUAN_DES" name="CABANG_TUJUAN" value="" readonly>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea type="text" rows="4" class="form-control" id="appnotifMUTASI_DESKRIPSI" name="MUTASI_DESKRIPSI" value="" data-parsley-required readonly></textarea>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Efektif</label>
                                <input type="text" class="form-control" id="appnotifTANGGAL_EFEKTIF" name="MUTAS_TANGGAL" value="" readonly>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-6 text-left">
                            <button type="submit" name="submit" id="notifapprovemutasianggota" class="submit btn btn-success mb5 btn-rounded"><i class="fa-regular fa-square-check"></i> Setuju</button>
                            <button type="submit" name="submit" id="notifrejectmutasianggota" class="submit btn btn-danger mb5 btn-rounded"><i class="fa-regular fa-rectangle-xmark"></i> Tolak</button>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" class="btn btn-inverse btn-outline mb5 btn-rounded next" data-dismiss="modal"><span class="ico-cancel"></span> Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="ViewNotifKas" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="ViewNotifKas-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Lihat Data Kas Anggota</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" align="center">
                            <div class="short-div">
                                <label>Foto Anggota </label><br>
                                <div id="notifkaspic"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Anggota</label>
                                    <input type="text" class="form-control" id="kasviewANGGOTA_IDNAMA" name="ANGGOTA_KEY" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div hidden">
                                <div class="form-group">
                                    <label>ID Cabang</label>
                                    <input type="text" class="form-control" id="kasviewCABANG_KEY" name="CABANG_KEY" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Daerah</label>
                                    <input type="text" class="form-control" id="kasviewDAERAH_DESKRIPSI" name="DAERAH_KEY" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Cabang</label>
                                    <input type="text" class="form-control" id="kasviewCABANG_DESKRIPSI" name="CABANG_DESKRIPSI" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Sabuk</label>
                                    <input type="text" class="form-control" id="kasviewTINGKATAN_NAMA" name="TINGKATAN_ID" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Tingkatan</label>
                                    <input type="text" class="form-control" id="kasviewTINGKATAN_SEBUTAN" name="TINGKATAN_SEBUTAN" value="" readonly>
                                </div> 
                            </div>   
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jenis Kas<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="kasviewKAS_JENIS" name="KAS_JENIS" value="" readonly>
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Saldo Awal</label>
                                <div class="input-group">
                                    <span class="input-group-addon">Rp</span>
                                    <input type="text" class="form-control text-right" id="kasviewKAS_SALDOAWAL" name="KAS_SALDOAWAL" value="" readonly>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Kategori</label>
                                <input type="text" class="form-control" id="kasviewKAS_DK" name="KAS_DK" value="" readonly>
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jumlah</label>
                                <div class="input-group">
                                    <span class="input-group-addon">Rp</span>
                                    <input type="text" class="form-control text-right" id="kasviewKAS_JUMLAH" name="KAS_JUMLAH" value="" readonly>
                                </div>
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Saldo Akhir</label>
                                <div class="input-group">
                                    <span class="input-group-addon">Rp</span>
                                    <input type="text" class="form-control text-right" id="kasviewKAS_SALDOAKHIR" name="KAS_SALDOAKHIR" value="" readonly>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea type="text" rows="4" class="form-control" id="kasviewKAS_DESKRIPSI" name="KAS_DESKRIPSI" value="" readonly></textarea>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Close</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="ViewNotifPPD" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="ViewNotifPPD-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Lihat Data PPD Anggota</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" align="center">
                            <div class="short-div">
                                <label>Foto Anggota </label><br>
                                <div id="notifpicppd"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Daerah</label>
                                <input type="text" class="form-control" id="ViewNotifPPD_DAERAH" name="DAERAH_KEY" readonly />
                            </div> 
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Cabang</label>
                                    <input type="text" class="form-control" id="ViewNotifPPD_CABANG" name="CABANG_KEY" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="text" class="form-control" id="ViewNotifPPD_TANGGAL" name="PPD_TANGGAL" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Jenis PPD</label>
                                    <input type="text" class="form-control" id="ViewNotifPPD_JENIS" name="PPD_JENIS" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Tingkatan Lanjutan</label>
                                    <input type="text" class="form-control" id="ViewNotifPPD_TINGKATAN" name="PPD_TINGKATAN" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Anggota</label>
                                    <input type="text" class="form-control" id="ViewNotifPPD_ANGGOTA" name="ANGGOTA_KEY" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Lokasi Cabang PPD</label>
                                    <input type="text" class="form-control" id="ViewNotifPPD_LOKASI" name="PPD_LOKASI" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Deskripsi</label>
                                    <textarea type="text" rows="4" class="form-control" id="ViewNotifPPD_DESKRIPSI" name="PPD_DESKRIPSI" value="" readonly></textarea>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Cancel</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="ApproveNotifPPDKoordinator" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="ApproveNotifPPDKoordinator-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Persetujuan Data PPD Anggota</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" align="center">
                            <div class="short-div">
                                <label>Foto Anggota </label><br>
                                <div id="notifpicppdkoor"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="short-div hidden">
                                <div class="form-group">
                                    <label>ID</label><span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="notifKoordinatorPPD_ID" name="PPD_ID" readonly required data-parsley-required/>
                                </div> 
                            </div>
                            <div class="form-group">
                                <label>Daerah</label>
                                <input type="text" class="form-control" id="notifKoordinatorPPD_DAERAH" name="DAERAH_KEY" readonly />
                            </div> 
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Cabang</label>
                                    <input type="text" class="form-control" id="notifKoordinatorPPD_CABANG" name="CABANG_KEY" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="text" class="form-control" id="notifKoordinatorPPD_TANGGAL" name="PPD_TANGGAL" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Jenis PPD</label>
                                    <input type="text" class="form-control" id="notifKoordinatorPPD_JENIS" name="PPD_JENIS" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Tingkatan Lanjutan</label>
                                    <input type="text" class="form-control" id="notifKoordinatorPPD_TINGKATAN" name="PPD_TINGKATAN" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Anggota</label>
                                    <input type="text" class="form-control" id="notifKoordinatorPPD_ANGGOTA" name="ANGGOTA_ID" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Lokasi Cabang PPD</label>
                                    <input type="text" class="form-control" id="notifKoordinatorPPD_LOKASI" name="PPD_LOKASI" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Deskripsi</label>
                                    <textarea type="text" rows="4" class="form-control" id="notifKoordinatorPPD_DESKRIPSI" name="PPD_DESKRIPSI" value="" readonly></textarea>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-6 text-left">
                            <button type="submit" name="submit" id="approveKoordinator" class="submit btn btn-success mb5 btn-rounded"><i class="fa-regular fa-square-check"></i> Setuju</button>
                            <button type="submit" name="submit" id="rejectKoordinator" class="submit btn btn-danger mb5 btn-rounded"><i class="fa-regular fa-rectangle-xmark"></i> Tolak</button>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" class="btn btn-inverse btn-outline mb5 btn-rounded next" data-dismiss="modal"><span class="ico-cancel"></span> Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="ViewNotifUKT" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="ViewNotifUKT-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Lihat Data UKT Anggota</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" align="center">
                            <div class="short-div">
                                <label>Foto Anggota </label><br>
                                <div id="loadpicnotifukt"></div>
                            </div>
                            <br>
                            <br>
                            <div class="short-div">
                                <div class="col-md-6">
                                    <h2><u>Nilai UKT</u></h2>
                                    <h2 id="ViewNotifUKT_TOTAL"></h2>
                                </div>
                                <div class="col-md-6">
                                    <h2><u>Predikat</u></h2>
                                    <h2 id="ViewNotifUKT_NILAI"></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Daerah </label>
                                    <input type="text" class="form-control" id="ViewNotifUKT_DAERAH" name="DAERAH_KEY" readonly/>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Cabang </label>
                                    <input type="text" class="form-control" id="ViewNotifUKT_CABANG" name="CABANG_KEY" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Tanggal </label>
                                    <input type="text" class="form-control" id="ViewNotifUKT_TANGGAL" name="UKT_TANGGAL" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Pilih Anggota </label>
                                    <input type="text" class="form-control" id="ViewNotifUKT_ANGGOTA" name="ANGGOTA_ID" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Tingkatan UKT </label>
                                    <input type="text" class="form-control" id="ViewNotifUKT_TINGKATAN" name="TINGKATAN_ID" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Lokasi Cabang UKT </label>
                                    <input type="text" class="form-control" id="ViewNotifUKT_LOKASI" name="UKT_LOKASI" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Deskripsi </label>
                                    <textarea type="text" rows="4" class="form-control" id="ViewNotifUKT_DESKRIPSI" name="UKT_DESKRIPSI" value="" readonly></textarea>
                                </div> 
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h4>Daftar Penguji</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-heading">
                                <h3 class="panel-title">Tabel Daftar Penguji</h3>
                            </div>
                            <table class="table table-striped table-bordered" id="viewNotifPenguji-table">
                                <thead>
                                    <tr>
                                        <th>No. </th>
                                        <th>Nama Penguji </th>
                                        <th>Tingkatan </th>
                                    </tr>
                                </thead>
                                <tbody id="viewNotifPengujiData">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <h4>Rincian Nilai UKT</h4>
                    <div id="viewnotifrincianukt"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Cancel</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="ApproveNotifUKTKoordinator" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="ApproveNotifUKTKoordinator-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Persetujuan Data UKT Anggota</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" align="center">
                            <div class="short-div">
                                <label>Foto Anggota </label><br>
                                <div id="loadPicAppNotifUKT"></div>
                            </div>
                            <br>
                            <br>
                            <div class="short-div">
                                <div class="col-md-6">
                                    <h2><u>Nilai UKT</u></h2>
                                    <h2 id="viewAppNotifUKT_TOTAL"></h2>
                                </div>
                                <div class="col-md-6">
                                    <h2><u>Predikat</u></h2>
                                    <h2 id="viewAppNotifUKT_NILAI"></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="short-div hidden">
                                <div class="form-group">
                                    <label>ID</label><span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="viewAppNotifUKT_ID" name="UKT_ID" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Daerah </label>
                                    <input type="text" class="form-control" id="viewAppNotifDAERAH_KEY" name="DAERAH_KEY" readonly/>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Cabang </label>
                                    <input type="text" class="form-control" id="viewAppNotifCABANG_KEY" name="CABANG_KEY" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Tanggal </label>
                                    <input type="text" class="form-control" id="viewAppNotifUKT_TANGGAL" name="UKT_TANGGAL" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Pilih Anggota </label>
                                    <input type="text" class="form-control" id="viewAppNotifANGGOTA_ID" name="ANGGOTA_ID" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Tingkatan UKT </label>
                                    <input type="text" class="form-control" id="viewAppNotifTINGKATAN_ID" name="TINGKATAN_ID" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Lokasi Cabang UKT </label>
                                    <input type="text" class="form-control" id="viewAppNotifUKT_LOKASI" name="UKT_LOKASI" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Deskripsi </label>
                                    <textarea type="text" rows="4" class="form-control" id="viewAppNotifUKT_DESKRIPSI" name="UKT_DESKRIPSI" value="" readonly></textarea>
                                </div> 
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h4>Daftar Penguji</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-heading">
                                <h3 class="panel-title">Tabel Daftar Penguji</h3>
                            </div>
                            <table class="table table-striped table-bordered" id="viewAppNotifPenguji-table">
                                <thead>
                                    <tr>
                                        <th>No. </th>
                                        <th>Nama Penguji </th>
                                        <th>Tingkatan </th>
                                    </tr>
                                </thead>
                                <tbody id="viewAppNotifPengujiData">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <h4>Rincian Nilai UKT</h4>
                    <div id="viewAppNotifrincianukt"></div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-6 text-left">
                            <button type="submit" name="submit" id="approveKoordinator" class="submit btn btn-success mb5 btn-rounded"><i class="fa-regular fa-square-check"></i> Setuju</button>
                            <button type="submit" name="submit" id="rejectKoordinator" class="submit btn btn-danger mb5 btn-rounded"><i class="fa-regular fa-rectangle-xmark"></i> Tolak</button>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" class="btn btn-inverse btn-outline mb5 btn-rounded next" data-dismiss="modal"><span class="ico-cancel"></span> Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>