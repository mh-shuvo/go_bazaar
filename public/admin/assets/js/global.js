//for url
var url  = $('meta[name = path]').attr("content");
function getSubCategory(category_id,selected_item = null,destination='sub_category'){
 $.ajax({
        url: url + "/getSubCategoryByCategory",
        type:"POST",
        dataType:"JSON",
        data:{
            category_id: category_id,
        },
        success:function(response){
            response.forEach(function(item) {
                if(selected_item !=null && item.id == selected_item){
                    document.getElementById(destination).innerHTML += "<option value='" + item.id + "' selected>" + item.name + "</option>";
                }
                else{
                    document.getElementById(destination).innerHTML += "<option value='" + item.id + "'>" + item.name + "</option>";
                }
            });
        }
    });
}

   function getProduct(category_id,sub_category_id,selected_item = null,destination= 'product'){
    $.ajax({
           url: url + "/getProduct",
           type:"POST",
           dataType:"JSON",
           data:{
               category_id: category_id,
               sub_category_id: sub_category_id ,
           },
           success:function(response){
               response.forEach(function(item) {
                   if(selected_item !=null && item.id == selected_item){
                       document.getElementById(destination).innerHTML += "<option value='" + item.id + "' selected>" + item.name + "</option>";
                   }
                   else{
                       document.getElementById(destination).innerHTML += "<option value='" + item.id + "'>" + item.name + "</option>";
                   }
               });
           }
       });
  };
  
  function openloader(){
     $("#LoaderModal").modal('toggle');
  }
  function closeloader(){
   $("#LoaderModal").modal('toggle'); 
  }