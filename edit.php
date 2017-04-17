<?php
      $_db_host = "localhost";
      $_db_user = "id1367301_root";
      $_db_pass = "12345";
      $_db_name = "id1367301_employees";
      $_mysqli =mysqli_connect($_db_host, $_db_user, $_db_pass,$_db_name );
      mysqli_query($_mysqli,"SET NAMES 'utf8'");
      mysqli_query($_mysqli,"SET CHARACTER SET 'utf8'");
      mysqli_query($_mysqli,"SET SESSION collation_connection = 'utf8_general_ci'");

      if (mysqli_connect_errno())
      {
            printf("Ошибка соединения: %s\n", mysqli_connect_error());
            exit();

      }

      $_res=mysqli_query($_mysqli,"SELECT * FROM employees_list WHERE id=".$_GET["id"]);
      $_row = mysqli_fetch_assoc($_res);
?>
<html>
 <head>
  <meta charset="utf-8">
  <title>Редактирование сотрудника</title>
  <link href="css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
  <link href="css/bootstrap/css/fileinput.css" rel="stylesheet">

   <script type="text/javascript" src="js/jquery/jquery-1.11.1.min.js"></script>
   <script type="text/javascript" src="js/locales/moment-with-locales.min.js"></script>
   <script type="text/javascript" src="js/bootstrap/bootstrap.min.js"></script>
   <script type="text/javascript" src="js/bootstrap/bootstrap-datetimepicker.min.js"></script>
   <script type="text/javascript" src="js/bootstrap/fileinput.js"></script>
   <script type="text/javascript" src="js/locales/ru.js"></script>

 </head>
 <body>
 <div style="height:80%; width:50%; margin-top: 10px;" class="center-block">
 <legend>Редактирование сотрудника</legend>
 <form class="form-horizontal" enctype="multipart/form-data" method="POST" id="edit_employee_form" action="javascript:void(null);" onsubmit="edit(this); return false;">
    <div class="form-group">
        <label for="last_name" class="col-sm-2 control-label">Фамилия <span style="color: #CC0000">*</span> </label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="last_name" name="last_name" required pattern="^[a-zA-Z-А-Яа-яЁё\s]{1,}"  value='<?= $_row['last_name']?>' />
          <input type="hidden" id="id" name="id" value='<?= $_GET["id"]?>' />
        </div>
      </div>
      <div class="form-group">
        <label for="first_name" class="col-sm-2 control-label">Имя <span style="color: #CC0000">*</span> </label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="first_name" name="first_name" required pattern="^[a-zA-Z-А-Яа-яЁё\s]{1,}" value='<?= $_row['first_name']?>' />
        </div>
      </div>
      <div class="form-group">
        <label for="middle_name" class="col-sm-2 control-label">Отчество </label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="middle_name" name="middle_name" pattern="^[a-zA-Z-А-Яа-яЁё\s]{1,}" value='<?= $_row['middle_name']?>' />
        </div>
      </div>

      <div class="form-group">
        <label for="age" class="col-sm-2 control-label">Возраст <span style="color: #CC0000">*</span></label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="age" required  pattern="^[ 0-9]{1,3}" value='<?= $_row['age']?>' />
          </div>
      </div>
    <div class="form-group">
        <label for="sex" class="col-sm-2 control-label">Пол <span style="color: #CC0000">*</span></label>
        <div class="col-sm-10">
           <select class="form-control" name="sex" id="sex" required>
           <?php
            if($_row["sex"]==1)
           {
                echo "<option selected value='1'>Мужской</option>
                <option value='2'>Женский</option>";
           }
           else
           {
               echo "<option value='1'>Мужской</option>
                <option selected value='2'>Женский</option>";
           }
           ?>
            </select>
        </div>
      </div>
     <div class="row">
     <div class="col-xs-6 col-md-3"></div>
     <div class="col-xs-6 col-md-3"></div>
  <div class="col-xs-6 col-md-3">
    <a href="#" class="thumbnail">
      <img src="data:image/jpeg;base64,<?= base64_encode($_row['photo'])?>" />
    </a>
  </div>
</div>
    <div class="form-group">
       <label for="photo" class="col-sm-2 control-label">Фото</label>
       <div class="col-sm-10">
        <input type="file" class="file" name="photo" id="photo">
       </div>
 </div>

  <div style="margin-left: 60px;"><span style="color: #CC0000">*</span>обязательные поля</div>
     <button type="button" onclick='location.href="index.html"' class="btn btn-danger" style="float:right;">Отмена</button>
     <button type="submit" class="btn btn-success" style="float:right; margin-right: 10px;">Сохранить</button>
  </form>

</div>
<script type="text/javascript" src="js/script.js"></script>
 </body>
</html>