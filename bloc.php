<?php 
    if(is_dir('./text_files')){
    }else{
      mkdir('./text_files');
      if(chmod('./text_files', 777)){
      }
    }
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $DIRSEP = "\\";
    } else {
        $DIRSEP = "/";
    }
    $counter = 1;
    $text_area_content = "";
    if(isset($_GET['action']) ) {
      $file_to_open_name = isset($_GET['action']) ? $_GET['action'] : '';
      $file_to_open = __DIR__ . "${DIRSEP}text_files${DIRSEP}${file_to_open_name}";
      $handle = fopen($file_to_open, 'r');
      $contents = fread($handle, filesize($file_to_open));
      fclose($handle);
      $text_area_content= $contents;
      $check_open = true;
      $current_open = $file_to_open;
      $current_open_name = $file_to_open_name;
    }
      //Get content and write in txt file
    if(isset($_GET['bloctexto'])) {
    $contents = $_GET['bloctexto'];
    /////////////
    $file_to_open_name = isset($_GET['past_action']) ? $_GET['past_action'] : 'Nada';
    if($_GET['past_action'] === 'Nada') {
      if($contents == ""){
        header('Location: bloc.php');
      }else{
        $text_area_content = 'No ha abierto ningún archivo...';
     }}else{
      $file_to_open = __DIR__ . "${DIRSEP}text_files${DIRSEP}${file_to_open_name}";
      $handle = fopen($file_to_open, 'w');
      fwrite($handle, $contents);
      fclose($handle);
/*       header('Location: bloc.php');
 */    }
  }
  if(isset($_GET['delete'])){
    $fileToDelete = $_GET['delete'];
    $pathToFile = __DIR__ . "${DIRSEP}text_files${DIRSEP}${fileToDelete}";
    unlink($pathToFile);
  }
  if(isset($_GET['fileName'])){
    $fileRaw = $_GET['fileName'];
    $fileName =  __DIR__ . "${DIRSEP}text_files${DIRSEP}${fileRaw}.txt";
    $handle = fopen($fileName, 'w');
    fwrite($handle, 'Nuevo archivo de texto...');
    fclose($handle);
  }






?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="" content="" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    />
    <link rel="stylesheet" href="./bloc.css" />

    <title>Title</title>
  </head>

  <body>
 
    <nav
      class="navbar navbar-dark navbar-expand-md bg-dark justify-content-center"
    >
      <div class="container-fluid">
        <button
          class="navbar-toggler"
          type="button"
          data-toggle="collapse"
          data-target=".dual-nav"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse dual-nav w-50 order-1 order-md-0">
          <div>
            <input id="buttonSave" type="submit" value="Guardar" form="paper">
          </div>
          <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="#" data-toggle="modal" data-target="#modal-archivos">Abrir</a>
          </li>
            <li class="nav-item">
              <a class="nav-link" href="#" data-toggle="modal" data-target="#modal-crear">Crear</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" data-toggle="modal" data-target="#modal-eliminar">Eliminar</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" data-toggle="modal" data-target="#modal-buscar">Buscar</a>
            </li>
            <?php 
            if(isset($_POST['fileContent'])){
               echo " <li class='nav-item'>
              <a class='nav-link' href='#' data-toggle='modal' data-target='#modal-verArchivos'>Ver archivos</a>
            </li>";
            }
             
            ?>
          </ul>
        </div>
        <div class="navbar-collapse collapse dual-nav w-50 order-2"></div>
      </div>
    </nav>

   <!--    Formulario para enviar el TextArea -->
    <form id="paper" method="GET" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <textarea
        placeholder="Escribe algo..."
        id="text"
        name="bloctexto"
        rows="20"
        style="
          overflow-y: scroll;
          word-wrap: break-word;
          resize: none;
          height: 85%;
        "
      ><?php echo $text_area_content ?></textarea>
      <input type="hidden" name="past_action" value="<?php if(isset($_GET['action'])){
        echo $_GET['action'];
      }else{
        echo 'Nada';
      } ?>" />
    </form>


<!--     Ventana Modal para visualizar los archivos
 -->  
 <div class="modal fade" id="modal-archivos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title ">Archivos guardados:</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php 
        $dir = './text_files';
        $allFiles = scandir($dir, SCANDIR_SORT_DESCENDING);
        $files = array_diff($allFiles, array('.', '..'));
        foreach ($files as $file) {
         echo "<a href='${_SERVER['PHP_SELF']}?action=${file}'>${file}</a>";
         echo '<br>';
        }
        ?>
      </div>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>

<!-- Ventana modal para crear archivos con nombres personalizados
 -->

 <div class="modal fade" id="modal-crear" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" >
    <div class="modal-content">
      <div class="modal-header text-center">
        <h5 class="modal-title w-100">Crear un nuevo archivo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="GET">
        <div class="form-div">
          <label for="#fileNameInput">Ingrese el nombre del archivo: </label>
          <input type="text" class="form-control" id="fileNameInput" name="fileName" placeholder="">
        </div>
        <button class="btn" id="buttonCrear" type="submit">Crear</button>
      </form>
      </div>
    </div>
  </div>
</div>

<!--         Ventana Modal para eliminar archivos
 -->

 <div class="modal fade" id="modal-eliminar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" >
    <div class="modal-content">
      <div class="modal-header text-center">
        <h5 class="modal-title w-100">Eliminar un archivo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <?php 
          $dir = './text_files';
          $allFiles = scandir($dir, SCANDIR_SORT_DESCENDING);
          $files = array_diff($allFiles, array('.', '..'));
          foreach ($files as $file) {
          echo "<a href='${_SERVER['PHP_SELF']}?delete=${file}'>${file}</a>";
          echo '<br>';
          }
        ?>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-buscar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" >
    <div class="modal-content">
      <div class="modal-header text-center">
        <h5 class="modal-title w-100">Buscar en un archivo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <form action="bloc.php" method='POST' >    
        <div class='form-div'>
          <label for='#fileNameInput'>Ingrese el texto que desea buscar: </label>
          <input type='text' class='form-control' id='fileContentInput' name='fileContent' placeholder=''>
        </div>
        <button class='btn' id='buttonCrear' type='submit'>Buscar</button>
        </form> 
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-verArchivos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" >
    <div class="modal-content">
      <div class="modal-header text-center">
        <h5 class="modal-title w-100">Archivos encontrados</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
          <?php 
            $dir = __DIR__ . '/text_files';
            $allFiles = scandir($dir, SCANDIR_SORT_DESCENDING);
            $files = array_diff($allFiles, array('.', '..'));
            $text = $_POST['fileContent'];
            $matchFile = [];
            foreach($files as $file){
                $path = "${dir}${DIRSEP}${file}";
                $handle = fopen($path, 'r');
                $contents = fread($handle, filesize($path));
                $contents = strtolower($contents); 
                $text = strtolower($text);
                fclose($handle);
                if(strpos($contents, $text) !== false){
                    $matchFile[] = $file;
                }      
            }
            foreach($matchFile as $match)
            echo "<a href='${_SERVER['PHP_SELF']}?action=${match}'>${match}</a><br>";
          ?>
      </div>
    </div>
  </div>
</div>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
  </body>
</html>
