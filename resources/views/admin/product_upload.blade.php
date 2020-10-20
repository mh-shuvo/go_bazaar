@extends("layouts.admin")
@section("title","CSV ফাইল আপলোড")
@section("content")
    <!-- Start container-fluid -->
    <div class="container-fluid">
        {{-- row start --}}
        <div class="row">
            <div class="col-12">
                <div>
                    <h4 class="header-title mb-3">CSV ফাইল আপলোড</h4>
                </div>
            </div>
        </div>

             <div class="row">

            <div class="col-sm-8 offset-sm-2">

                <div class="card-box">
                  <form class="AddBulkProductForm" id="AddBulkProductForm" action="javascript:void(0)" enctype="multipart/form-data"
                method="POST">
                        <div class="form-group">
                            <label class="col-form-label">CSV ফাইল নির্বাচন করুন:</label>
                            <input class="form-control-file product_csv_file" type="file" name="product_csv_file" accept=".csv,.xlsx">
                        </div>
                        <hr>

                        <center>
                              <button type="submit" class="btn btn-primary waves-effect waves-light"
                            id="submit_btn"> <i class="fa fa-upload"></i> CSV আপলোড করুন</button>
                            <button type="button" class="btn btn-info waves-effect waves-light"
                            id="check_btn"> <i class="fa fa-upload"></i> CSV Check korun করুন</button>
                            
                        </center>
                </form>
                </div>
                <div class="card-box error_section">
                  <div class="alert alert-danger d-none" role="alert">
                    <strong>Errors:</strong>
                    <ul></ul>
                  </div>
                   <div class="alert alert-success d-none" role="alert"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- end container-fluid -->
@endsection

@section('js')
<script type="text/javascript">

$(document).on('submit','#AddBulkProductForm',function(){
  let csv = $("#AddBulkProductForm .product_csv_file").val() || null;
 
  if(csv == null){
      toastr.error("CSV ফাইল নির্বাচন করুন");
      return;
  }
  else{
    openloader();
    $.ajax({
      url: "{{route('csv.upload')}}",
      type: "POST",
      data: new FormData(this),
      processData: false,
      contentType: false,
      success: function (response) {
        closeloader();

        $(".error_section .alert-success").html('');
        $(".error_section .alert-danger ul").html('');

        if(response.status == 'error'){
          $(".error_section .alert-success").addClass('d-none');
          $(".error_section .alert-danger ul").html('');
          let error_list = htmlRender(response.errors);
          $(".error_section .alert-danger ul").html(error_list);
          $(".error_section .alert-danger").removeClass('d-none');
          document.getElementById("AddBulkProductForm").reset();
        }
        if(response.status == 'success'){
          document.getElementById("AddBulkProductForm").reset();
          toastr.success(response.msg)
        }
      },
    });
  }

});
$(document).on('click','#check_btn',function(){
  let csv = $("#AddBulkProductForm .product_csv_file").val() || null;

  let myForm = document.getElementById('AddBulkProductForm');
  let formData = new FormData(myForm);

  if(csv == null){
      toastr.error("CSV ফাইল নির্বাচন করুন");
      return;
  }
  else{
    openloader();
    $.ajax({
      url: "{{route('csv.check')}}",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        $(".error_section .alert-success").html('');
        $(".error_section .alert-danger ul").html('');
        if(response.status == 'success'){
          closeloader();
          $(".error_section .alert-danger").addClass('d-none');
          $(".error_section .alert-success").html('<strong class="text-center">'+response.msg+'</strong>');
          $(".error_section .alert-success").removeClass('d-none');
        }
        if(response.status == 'error'){
          closeloader();
          $(".error_section .alert-success").addClass('d-none');
          $(".error_section .alert-danger ul").html('');
          let error_list = htmlRender(response.errors);
          $(".error_section .alert-danger ul").html(error_list);
          $(".error_section .alert-danger").removeClass('d-none');
        }
        document.getElementById("AddBulkProductForm").reset();
      },
    });
  }
});
function htmlRender(errors){
  var list_item = '';
  errors.forEach(function(error){
    list_item += '<li>'+error+'</lii>';
  });
  return list_item;
}
</script>
@endsection