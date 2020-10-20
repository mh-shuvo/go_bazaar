//for url
var url  = $('meta[name = path]').attr("content");
var csrf    = $('mata[name = csrf-token]').attr("content");

$(document).on('click','.AddAccountHead',function(){
	$("#AddAccountHeadForm")[0].reset();
	$("#AddAccountHeadModal").modal('toggle');
});
$(document).on("submit","#AddAccountHeadForm",function(e){
	e.preventDefault();
	let name = $("#name").val() || 0;
	if(name == 0){
		toastr.warning("নাম প্রদান করুন");
	}
	else{
		$.ajax({
            url: url + '/expense/account-head/store',
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (response) {

               if(response.status=="success"){
               	toastr.success(response.message);
               }
               if(response.status == 'error'){
               	toastr.error(response.message);
               }

               $("#AddAccountHeadForm")[0].reset();
               $("#AddAccountHeadModal").modal('toggle');
               $('.category_table').DataTable().draw(true);

            }
        });
	}

});

$(document).on("click",".EditAccountHead",function(){
	row_index = $(this).data('row');
	data = $('.category_table').DataTable().row(row_index).data();
	$("#name").val(data.name);
	$("#id").val(data.id);
	$("#AddAccountHeadModal").modal('toggle');
});

$(document).on("click",".DeleteAccountHead",function(){
	row_index = $(this).data('row');
	data = $('.category_table').DataTable().row(row_index).data();
	  Swal.fire({
      type:'warning',
      title: 'আপনি কি ডিলিট করতে চান ?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'হ্যাঁ',
      cancelButtonText: 'না',
    }).then((result) => {
      if (result.value) {

        $.ajax({
                url: url + '/expense/account-head/delete',
                type: "POST",
                dataType: "JSON",
                data: {
                    id :data.id,
                   
                },
                success: function(response) {

                     if(response.status == "success"){
		               	toastr.success(response.message);
		               }
		               if(response.status == 'error'){
		               	toastr.error(response.message);
		               }

                     $('.category_table').DataTable().draw(true);
                }
            });
      }
    });

});


$(document).on('click','.AddExpenseEntry',function(){
	$("#AddExpenseEntryForm")[0].reset();
	$("#AddExpenseEntryModal").modal('toggle');
});
$(document).on("submit","#AddExpenseEntryForm",function(e){
	e.preventDefault();
	let account_head = $("#account_head").val() || 0;
	let amount = $("#amount").val() || '';
	if(account_head == 0){
		toastr.warning("ক্যাটাগরি প্রদান করুন");
	}
	if(amount == ''){
		toastr.warning("টাকার পরিমাণ উল্লেখ করুন");
	}
	else{
		$.ajax({
            url: url + '/expense/entry/store',
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (response) {

               if(response.status=="success"){
               	toastr.success(response.message);
               }
               if(response.status == 'error'){
               	toastr.error(response.message);
               }

               $("#AddExpenseEntryForm")[0].reset();
               $("#AddExpenseEntryModal").modal('toggle');
               $('.entry_table').DataTable().draw(true);

            }
        });
	}

});

$(document).on("click",".EditExpenseEntry",function(){
	row_index = $(this).data('row');
	data = $('.entry_table').DataTable().row(row_index).data();
	$("#account_head").val(data.account_head);
	$("#amount").val(data.amount);
	$("#note").html(data.note);
	$("#id").val(data.id);
	$("#AddExpenseEntryModal").modal('toggle');
});

$(document).on("click",".DeleteExpenseEntry",function(){
	row_index = $(this).data('row');
	data = $('.entry_table').DataTable().row(row_index).data();
	  Swal.fire({
      type:'warning',
      title: 'আপনি কি ডিলিট করতে চান ?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'হ্যাঁ',
      cancelButtonText: 'না',
    }).then((result) => {
      if (result.value) {

        $.ajax({
                url: url + '/expense/entry/delete',
                type: "POST",
                dataType: "JSON",
                data: {
                    id :data.id,
                   
                },
                success: function(response) {

                     if(response.status == "success"){
		               	toastr.success(response.message);
		               }
		               if(response.status == 'error'){
		               	toastr.error(response.message);
		               }

                     $('.entry_table').DataTable().draw(true);
                }
            });
      }
    });

});