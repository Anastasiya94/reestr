 $('#date').datetimepicker
 (
      {pickTime: false, language: 'ru'}
 );

 $("#photo").fileinput({
        allowedFileExtensions: ['jpg', 'png', 'gif'],
        showUpload: false,
        language: 'ru',
        maxFileSize: 200,
 });

 function zoom(obj)
 {
	  	var img = $(obj);
		var src = img.attr('src');
		$("body").append("<div class='popup'>"+
						 "<div class='popup_bg'></div>"+
						 "<img src="+src+" class='popup_img' />"+
						 "</div>");
		$(".popup").fadeIn(800);
		$(".popup_bg").click(function()
        {
			$(".popup").fadeOut(800);
			setTimeout(function()
            {
			  $(".popup").remove();
			}, 800);
		});

 };

 function search(page)
 {
        var form = $('#search_employees_form').serialize();

        $.ajax({
          type: 'POST',
          url: 'php/employees.php?act=get_search&page='+page,
          data: form,
          success: function(data) {
            $('#result_search').html(data);
          },
          error:  function(xhr, str){
	          alert('Возникла ошибка: ' + xhr.responseCode);
          }
        });
 }

 function remove(id)
 {
     if(confirm("Удалить ссотрудника?"))
     {
         $.ajax({
          type: 'GET',
          url: 'php/employees.php?del=delete',
          data:
          {
              id:id?id:0,
          },
          success: function(data)
          {
              var text = "Операция выполнена успешно";
               var code = 1;
              getMessage(text,code);
               search(1);
          },
          error:  function(xhr, str){
	          alert('Возникла ошибка: ' + xhr.responseCode);
          }
        });
     }

 }

 function add(obj)
 {
     var data = new FormData(obj);

     $.ajax({
          type: 'POST',
          url: 'php/employees.php?add=add',
          data:data,
           processData: false,
           contentType: false,
          success: function(data)
          {
              window.location.href = 'index.html';
              var text = "Операция выполнена успешно";
               var code = 1;
               getMessage(text,code);
          },
          error:  function(xhr, str){
	          alert('Возникла ошибка: ' + xhr.responseCode);
          }
        });
 }

 function edit(obj)
 {
     var data = new FormData(obj);

     $.ajax({
          type: 'POST',
          url: 'php/employees.php?edit=edit',
          data:data,
           processData: false,
           contentType: false,
          success: function(data)
          {
              window.location.href = 'index.html';
              var text = "Операция выполнена успешно";
               var code = 1;
               getMessage(text,code);
          },
          error:  function(xhr, str){
	          alert('Возникла ошибка: ' + xhr.responseCode);
          }
        });
 }

 function editForm(id)
 {
     window.location.href = 'edit.php?id='+id;

 }


 function getMessage(text,type)
 {
     Notify = {
                generate: function (aText, aOptHeader, aOptType_int)
                {
                    var ltypes = ['alert-info', 'alert-success', 'alert-warning', 'alert-danger'];
                    var ltype = ltypes[type];

                    var lText = '';
                    if (aOptHeader) {
                        lText += "<h4>"+aOptHeader+"</h4>";
                    }
                    lText += "<p>"+aText+"</p>";
                    var lNotify_e = $("<div class='alert "+ltype+"'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button>"+lText+"</div>");

                    setTimeout(function () {
                        lNotify_e.alert('close');
                    }, 3000);
                    lNotify_e.appendTo($("#notifies"));
                }

            }

            Notify.generate('', text, type);
 }

