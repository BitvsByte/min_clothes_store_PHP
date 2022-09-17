<?php
//inicializar variables a utilizar en el documento html
$unidades = $color = $envio = $resultado = $precio_final = $precio_total = $radio = $info = $mensajes = null;

// declarar las constantes 
const PRECIO_BLANCAS = 10;
const PRECIO_COLOR = 12;
const ENVIO = 20;


//comprobar si nos llega el formulario
if (isset($_POST['calcular'])) {
    //recuperar los datos del formulario
    $unidades = $_POST['unidades'];
    if (!empty($_POST['color'])) {
        $color = $_POST['color'];
    }
    if (!empty($_POST['envio'])) {
        $radio = $_POST['envio'];
    }
    try {
        //validar camisetas
        if (!is_numeric($unidades)) {
            throw new Exception("Error, esta casilla debe ser un numero", 100);
        }
        if ($unidades < 1) {
            throw new Exception("Solicitud minima 1 unidad", 101);
        }
        $precio_final = costeCamisetas($color);
        $precio_total = costeTotal($unidades, $precio_final, $radio);
    } catch (Exception $error) {
        $mensajes = $error->getCode() . ' ' .  $error->getMessage();
    }

    // MENSAJES DEL TEXT AEREA

    if ($unidades > 0) {
        $info = "El pedido consta de $unidades de color $color \n";
        $info .= "¿Ha marcado el envio?  $radio \n";
        $info .= "El precio final es $precio_total €";
    } else {
        $info='';
    }
    if($radio=="No"){
        $info = "El pedido consta de $unidades de color $color \n";
        $info .= "Ha No en la casilla Envio por tanto su pedido no tendra gastos añadidos \n";
        $info .= "El precio final es $precio_total €";

    }else{
        $info = "El pedido consta de $unidades de color $color \n";
        $info .= "Ha marcado SI en la casilla Envio por tanto su pedido tendra 20€ de gastos añadidos \n";
        $info .= "El precio final es $precio_total €";

    }

}
function costeCamisetas($color)
{
    switch ($color) {
        case 'blanca':
            return PRECIO_BLANCAS;
            break;
        case 'negra':
        case 'roja':
        case 'azul':
        case 'amarilla':
            return PRECIO_COLOR;
            break;

        default:
            //lanzar una excepción
            throw new Exception('No es un color', 100);
    }
}
function costeTotal($unidades, $precio_unidad, $radio)
{

    if (!is_numeric($unidades) || $unidades <= 0) {
        throw new Exception("Error, esta casilla debe ser un numero", 103);
    }
    //cálculo del precio según color y cantidad
    $precio_total = $precio_unidad * $unidades;

    //aplicar descuento de 10% si se han seleccionado 10 o más camisetas
    if ($unidades >= 10) {
        $precio_total = $precio_total * 0.9;
    }
    //validar que se ha seleccionado el radio
    if ($radio == 'Si') {
        $precio_total = $precio_total + ENVIO;
    }
    // validar que se selecciona radio
    if (!isset($_POST['envio'])) {
        throw new Exception("Debe selecion una de las 2 opciones", 102);
    }
    return $precio_total;
}
// mensaje final
  
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Compra camisetas</title>
    <style>
        html{background-image: url('https://www.metropoliabierta.com/uploads/s1/58/98/03/interior-de-boo-store-boo-barceloa.png');
            background-position: center center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }
        .container{ 
            margin-top: 100px;
            padding: 10px;      
            }
        form {
            width: 400px;
            margin:auto;
            
            height: 300px;box-shadow: 5px 5px 15px 3px #000000;
            padding-top: 20px;
            padding-left: 20px;
            
            border-radius: 18px;
            background: #e0e0e0;
            box-shadow:  18px 18px 14px #282727,
             -18px -18px 14px #ffffff;}
        label {
            width: 100px;
            display: inline-block;
            font-family: Arial, Helvetica, sans-serif;
            }
        p{color:red;font-family: fantasy;}
        textarea{
            color:green;
            font-family: Arial, Helvetica, sans-serif;
            width: 300px;
            height: 100px;
            box-shadow: 5px 5px 15px 5px #000000;

        }
        
    </style>
</head>
<body>
    <div class="container">
    <form action="#" method="post">
        <label>Unidades</label>
        <input type="number" name='unidades' Value=<?=$unidades?>>
        <br><br>
        <label>Color</label>
        <select name="color">
            <option disabled selected>Seleccione color</option>
            <option <?php if ($color == 'blanca') {echo 'selected';} ?>>blanca</option>
            <option <?php if ($color == 'negra') {echo 'selected';} ?>>negra</option>
            <option <?php if ($color == 'roja') {echo 'selected';} ?>>roja</option>
            <option <?php if ($color == 'azul') {echo 'selected';} ?>>azul</option>
            <option <?php if ($color == 'verde') {echo 'selected';} ?>>verde</option>
            <option <?php if ($color == 'amarilla') {echo 'selected';} ?>>amarillo</option>
        </select>
        <br><br>
        <label>Envío</label>
        <span>Si</span><input type="radio" name='envio' value = 'Si'>
        <span>No</span><input type="radio" name='envio' value = 'No' checked>
        <br><br>
        <input type="submit" name='calcular' value='Calcular precio'>
        <br><br>
        <textarea cols="36" rows="3" disabled><?=$info?></textarea>
        <p><?=$mensajes?></p>
    </form>

    </div>
    
</body>
</html>