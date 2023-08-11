
<?= $this->extend('tema/tema'); ?> 
<?=$this->section('css');?>
<!-- Data Table CSS -->
<link href="<?=base_url();?>/assets/alertifyjs/css/alertify.min.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url();?>/assets/alertifyjs/css/themes/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url();?>/assets/admincast/dist/assets/vendors/DataTables/datatables.min.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url();?>/assets/admincast/dist/assets/vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />

<?=$this->endSection();?>

<?=$this->section('content'); ?>
<div class="page-heading">
    <h1 class="page-title">Post Body</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?=base_url('home');?>"><i class="fa fa-home font-20"></i></a>
        </li>
        <li class="breadcrumb-item">Blog</li>
        <li class="breadcrumb-item">Post Body</li>
    </ol>
</div>

<!-- Container -->
<div class="page-content fade-in-up">
    <!-- Row -->
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Data Post Body</div>
                    <div class="ibox-tools">
                        <a onclick="reload_table()" class="refresh" data-toggle="tooltip" data-placement="top" title="reload data"><i class="fa fa-refresh"></i></a>
                        <?php if(enforce(3, 2)): ?>
                            <a class="" onclick="tambah_data()" data-toggle="tooltip" data-placement="top" title="tambah data"><i class="fa fa-plus-square"></i></a>
                        <?php endif ?>
                    </div>
                </div>
                <div class="ibox-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="15%">Action</th>
                                    <th width="10%">No</th>
									<th style="width: 18.75%">Post Id</th>
									<th style="width: 18.75%">Content</th>
									<th style="width: 18.75%">Categori</th>
									<th style="width: 18.75%">Order</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Row -->
</div>
<!-- /Container -->

<div class="modal fade" id="modalpostbody" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <?=form_open('', ['id' => 'formpostbody'], ['post_body_id' => '', 'method' => '', 'val_post_id' => $post['post_id']]);?>
            <?=csrf_field();?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
					<div class="form-group">
                        <?=form_label('Content');?>
                        <?=form_textarea('val_post_body_content', '', ['class' => 'form-control', 'rows' => 3]);?>
                    </div>
					<div class="form-group">
                        <?=form_label('Categori');?>
                        <?=form_dropdown('val_post_body_categori', categories(), '', ['class' => 'form-control']);?>
                    </div>
					<div class="form-group">
                        <?=form_label('Order');?>
                        <?=form_input('val_post_body_order', '', ['class' => 'form-control'], 'number');?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                </div>
            </div>
        <?=form_close();?>
    </div>
</div>
<?=$this->endSection();?>
<?=$this->section('js');?>
<script src="<?=base_url(); ?>/assets/alertifyjs/alertify.min.js" type="text/javascript"> </script>
<script src="<?=base_url(); ?>/assets/admincast/dist/assets/vendors/jquery-validation/dist/jquery.validate.min.js" type="text/javascript"> </script>
<script src="<?=base_url(); ?>/assets/admincast/dist/assets/vendors/DataTables/datatables.min.js" type="text/javascript"> </script>
<script src="<?=base_url(); ?>/assets/admincast/dist/assets/vendors/moment/min/moment.min.js" type="text/javascript"> </script>
<script src="<?=base_url(); ?>/assets/admincast/dist/assets/vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"> </script>

<script>
    var table;
    var save_method;
    $(document).ready(function () {
        table = $('#datatable').DataTable({
                    scrollCollapse: true,
                    responsive: true,
                    autoWidth: false,
                    language: { search: "",
                        searchPlaceholder: "Search",
                        sLengthMenu: "_MENU_items"
                    },
                    "order": [],
                    "ajax": {
                        "url": "<?php echo base_url('/blog/postbody/ajax_list') ?>",
                        "headers": {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        "type": "POST",
                        "data": {<?=csrf_token();?>: '<?=csrf_hash()?>'},
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR.responseText);
                        }
                    },
                    //optional
                    "columnDefs": [{
                        "targets": [0, 1],
                        "orderable": false,
                    }, ],
                });
    });
    
    function reload_table() {
        table.ajax.reload(null, false);
    }

    $(document).ready(function () {
	});
    

    function reset_form() {
        var MValid = $("#formpostbody");
        MValid[0].reset();
        MValid.find(".is-invalid").removeClass("is-invalid");
        MValid.find(".is-valid").removeClass("is-valid");
        
    }

    function lihat_data(id) {
        reset_form();
        save_method = 'update';
        $('#formpostbody').valid();
        $('[name="method"]').val('update');
        $('#formpostbody .form-control').addClass('form-view-detail');
        $('#formpostbody .form-control').prop('disabled', true);
        $('#formpostbody button[type="submit"]').hide();
        $.ajax({
            type: "GET",
            url: "<?=base_url('/blog/postbody');?>/"+id+'/get_data',
            dataType: "JSON",
            success: function (response) {
                $('#modalpostbody').modal('show');
                $('#modalpostbody .modal-title').text('Detail Data');
                $('[name="post_body_id"]').val(response.post_body_id);
                $('[name="val_post_id"]').val(response.post_id);
				$('[name="val_post_body_content"]').val(response.post_body_content);
				$('[name="val_post_body_categori"]').val(response.post_body_categori);
				$('[name="val_post_body_order"]').val(response.post_body_order);
				
            }
        });
    }

    <?php if(enforce(3, 2)): ?>
    function tambah_data() {
        save_method = 'save';
        reset_form();
        $('#modalpostbody').modal('show');
        $('#modalpostbody .modal-title').text('Tambah Data');
        $('[name="method"]').val('save');
        $('[name="post_body_id"]').val(null);
        $('#formpostbody .form-control').removeClass('form-view-detail');
        $('#formpostbody .form-control').prop('disabled', false);
        $('#formpostbody button[type="submit"]').show();
    }
    <?php endif ?>

    <?php if(enforce(3, 3)): ?>
    function edit_data(id) {
        reset_form();
        save_method = 'update';
        $('#formpostbody').valid();
        $('[name="method"]').val('update');
        $('#formpostbody .form-control').removeClass('form-view-detail');
        $('#formpostbody .form-control').prop('disabled', false);
        $('#formpostbody button[type="submit"]').show();
        $.ajax({
            type: "GET",
            url: "<?=base_url('/blog/postbody');?>/"+id+'/get_data',
            dataType: "JSON",
            success: function (response) {
                $('#modalpostbody').modal('show');
                $('#modalpostbody .modal-title').text('Edit Data');
                $('[name="post_body_id"]').val(response.post_body_id);
                $('[name="val_post_id"]').val(response.post_id);
				$('[name="val_post_body_content"]').val(response.post_body_content);
				$('[name="val_post_body_categori"]').val(response.post_body_categori);
				$('[name="val_post_body_order"]').val(response.post_body_order);
				
            }
        });
    }
    <?php endif ?>

    <?php if(enforce(3, 4)): ?>
    function delete_data(id) {
        Swal.fire({
        title: 'Apa Anda Yakin?',
        text: "Anda Tidak Dapat Mengembalikan Data Yang Telah Di Hapus",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya Hapus !'
        }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "DELETE",
                url: "<?=base_url('/blog/postbody')?>/"+id+'/delete_data',
                dataType: "json",
                success: function (response) {
                    if(response.errorCode == 1) {
                        Swal.fire(
                            'Deleted!',
                            'Data Berhasil Di Hapus.',
                            'success'
                        )
                    } else {
                        Swal.fire(
                            'Deleted Failed!',
                            'Data Gagal Di Hapus',
                            'warning'
                        )
                    }
                    reload_table();
                }
            });
        }
        })

    }
    <?php endif ?>

    $(function() {
        $('#formpostbody').validate({
            errorClass: "invalid-feedback",
            rules: {
			val_post_id: {
                required: true,
				maxlength: 11
            },

			val_post_body_content: {
                required: true,
            },

			val_post_body_categori: {
                required: true,
				maxlength: 11
            },

			val_post_body_order: {
                required: true,
				maxlength: 11
            },

            },
            messages: {
				val_post_id: {
                    required:'Post Id harus diisi',maxlength: 'Post Id Tidak Boleh Lebih dari 11 Huruf'
                },

				val_post_body_content: {
                    required:'Content harus diisi',
                },

				val_post_body_categori: {
                    required:'Categori harus diisi',maxlength: 'Categori Tidak Boleh Lebih dari 11 Huruf'
                },

				val_post_body_order: {
                    required:'Order harus diisi',maxlength: 'Order Tidak Boleh Lebih dari 11 Huruf'
                },


            },
            highlight: function(e) {
                $(e).closest(".form-control").removeClass("is-valid").addClass("is-invalid");
            },
            unhighlight: function(e) {
                $(e).closest(".form-control").removeClass("is-invalid").addClass("is-valid");
            },
            submitHandler: function() {
                var url = '';
                if(save_method == 'update') {
                    url = "<?=base_url('/blog/postbody/update_data');?>";
                }
                if(save_method == 'save') {
                    url = "<?=base_url('/blog/postbody/save_data');?>";
                }
                var formData = new FormData($($('#formpostbody'))[0]);
                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    dataType: "JSON",
                    processData: false,
                    contentType:false,
                    cache : false,
                    success: function (response) {
                        if(response.errorCode == 1) {
                            alertify.set('notifier', 'position', 'top-right');
                            alertify.notify('<span><i class="fa fa-bell"></i> ' + response.errorMessage + '</span>', response.errorType, 5, function() {
                                console.log('dismissed');
                            });
                            reload_table();
                            $('#modalpostbody').modal('hide');
                        } else {
                            toast_error(response.errorMessage);
                        }
                    },
                    error: function(jqXHR){
                        console.log(jqXHR.responseText);
                    }
                });
            }
        });
    });
</script>
<?=$this->endSection();?>
