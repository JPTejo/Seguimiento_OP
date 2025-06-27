<?php
// Conexion a base de datos
require_once 'conexion.php';

// Obtener usuarios y roles
$usuarios = $conn->query("SELECT u.id, u.nombre_usuario, r.id as rol_id, r.nombre AS rol FROM usuarios u JOIN roles r ON u.rol_id = r.id");
$usuarios_array = [];
$usuariosRolesJS = [];
foreach ($usuarios as $row) {
    $usuarios_array[] = $row;
    // Aquí guardamos el rol_id, para luego mapearlo con JS
    $usuariosRolesJS[$row['id']] = (int)$row['rol_id'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Planificación</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1BAAA0;
            --secondary-color: #16968d;
            --light-bg: #f5f9fa;
            --dark-text: #2c3e50;
            --border-color: #e1e8ed;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #e0f7fa, #f5f9fa);
            color: var(--dark-text);
            min-height: 100vh;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .form-card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow);
            overflow: hidden;
            margin: 20px 0;
            transition: var(--transition);
        }
        
        .form-header {
            background: linear-gradient(120deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 25px 30px;
            text-align: center;
        }
        
        .form-header h1 {
            font-size: 2.2rem;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .form-header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }
        
        .form-body {
            padding: 30px;
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
        }
        
        @media (min-width: 992px) {
            .form-body {
                grid-template-columns: 1fr 1fr;
            }
        }
        
        .form-section {
            background: var(--light-bg);
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: var(--transition);
        }
        
        .form-section:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .section-title {
            color: var(--primary-color);
            font-size: 1.4rem;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--primary-color);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .section-title i {
            font-size: 1.6rem;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #4a6572;
        }
        
        .required::after {
            content: " *";
            color: #e74c3c;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 14px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            transition: var(--transition);
            background: white;
        }
        
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(27, 170, 160, 0.2);
        }
        
        .campo {
            display: none;
            animation: fadeIn 0.4s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .icon-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background: rgba(27, 170, 160, 0.05);
            border-radius: 12px;
            border: 1px dashed var(--primary-color);
        }
        
        .icon-container i {
            font-size: 8rem;
            color: rgba(27, 170, 160, 0.15);
            transition: var(--transition);
        }
        
        .form-footer {
            padding: 25px 30px;
            background: #f8fafb;
            border-top: 1px solid var(--border-color);
            text-align: center;
        }
        
        .btn-submit {
            background: linear-gradient(120deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 16px 45px;
            font-size: 1.2rem;
            font-weight: 600;
            border-radius: 50px;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(27, 170, 160, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 20px rgba(27, 170, 160, 0.4);
        }
        
        .btn-submit:active {
            transform: translateY(0);
        }
        
        .responsive-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        @media (min-width: 768px) {
            .responsive-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
        
        .role-section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid #eef5f7;
            margin-top: 20px;
        }
        
        .role-title {
            color: var(--primary-color);
            font-size: 1.1rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .action-selector {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            background-color: white;
            margin-top: 5px;
        }
        
        .device-indicator {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            z-index: 1000;
            display: none;
        }
        
        @media (max-width: 576px) {
            .device-indicator::after { content: "Extra pequeño (XS)"; }
            .form-body {
                padding: 15px;
            }
            .form-section {
                padding: 15px;
            }
        }
        
        @media (min-width: 576px) and (max-width: 767px) {
            .device-indicator::after { content: "Pequeño (SM)"; }
        }
        
        @media (min-width: 768px) and (max-width: 991px) {
            .device-indicator::after { content: "Mediano (MD)"; }
        }
        
        @media (min-width: 992px) and (max-width: 1199px) {
            .device-indicator::after { content: "Grande (LG)"; }
        }
        
        @media (min-width: 1200px) {
            .device-indicator::after { content: "Extra grande (XL)"; }
        }
    </style>
</head>
<body>
    <div class="device-indicator"></div>
    
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1><i class="fas fa-clipboard-list"></i> Formulario de Planificación</h1>
                <p>Sistema de gestión de operaciones de producción</p>
            </div>
            
            <div class="form-body">
                <div class="form-section">
                    <h2 class="section-title"><i class="fas fa-user"></i> Selección de Usuario</h2>
                    
                    <div class="form-group">
                        <label for="usuario" class="required">Selecciona Usuario:</label>
                        <select id="usuario" required onchange="mostrarCampos(); sincronizarUsuario()">
                            <option value="">-- Seleccionar --</option>
                            <?php foreach ($usuarios_array as $usuario): ?>
                                <option value="<?php echo $usuario['id']; ?>">
                                    <?php echo htmlspecialchars($usuario['nombre_usuario']); ?> (<?php echo htmlspecialchars($usuario['rol']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div id="numero_op_contenedor" class="campo form-group">
                        <label for="numero_op" class="required">Ingrese el número de OP:</label>
                        <input type="text" id="numero_op" name="numero_op">                                     
                    </div>
                    
                    <div class="role-section">
                        <h3 class="role-title"><i class="fas fa-tasks"></i> Acciones según Rol</h3>
                        
                        <div class="form-group">
                            <label for="accion_rol" class="required">¿Qué debe de realizar?</label>
                            <select id="accion_rol" class="action-selector" onchange="mostrarCamposPorAccion()">
                                <option value="">-- Seleccionar acción --</option>
                                <!-- Las opciones se cargarán dinámicamente -->
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h2 class="section-title"><i class="fas fa-file-alt"></i> Detalles de Planificación</h2>
                    
                    <form id="formularioCampos" method="POST" action="guardar_planificacion.php" enctype="multipart/form-data">
                        <input type="hidden" name="usuario_id" id="usuario_id_oculto" value="">
                        
                        <div class="responsive-grid">
                            <div>
                                <!-- Comunes -->
                                <div id="cliente" class="campo form-group">
                                    <label>Cliente:</label>
                                    <input type="text" name="cliente">
                                </div>
                                
                                <!-- Planificación -->
                                <div id="mp_solicitada" class="campo form-group">
                                    <label>Materia Prima Solicitada:</label>
                                    <input type="text" name="mp_solicitada">
                                </div>
                                
                                <div id="mp_sustituta" class="campo form-group">
                                    <label>Materia Prima Sustituta:</label>
                                    <input type="text" name="mp_sustituta">
                                </div>
                                
                                <div id="cant_programada" class="campo form-group">
                                    <label>Cantidad Programada:</label>
                                    <input type="number" name="cant_programada">
                                </div>
                                
                                <div id="cant_solicitada" class="campo form-group">
                                    <label>Cantidad Solicitada:</label>
                                    <input type="number" name="cant_solicitada">
                                </div>
                                
                                <div id="mp_utilizada" class="campo form-group">
                                    <label>Materia Prima Utilizada:</label>
                                    <input type="text" name="mp_utilizada">
                                </div>
                                
                                <div id="cant_realizada" class="campo form-group">
                                    <label>Cantidad Realizada:</label>
                                    <input type="number" name="cant_realizada">
                                </div>
                                
                                <!-- Bodega -->
                                <div id="cant_entregada" class="campo form-group">
                                    <label>Cantidad Entregada:</label>
                                    <input type="number" name="cant_entregada">
                                </div>
                                
                                <div id="razon_solicitud" class="campo form-group">
                                    <label>Razón - Solicitud cambio de Materia Prima:</label>
                                    <input type="text" name="razon_solicitud">
                                </div>
                                
                                <div id="entregado_a" class="campo form-group">
                                    <label>Entregado a:</label>
                                    <input type="text" name="entregado_a">
                                </div>
                            </div>
                            
                            <div>
                                <!-- Máquinas -->
                                <div id="maq1" class="campo form-group">
                                    <label>Máquina Utilizada:</label>
                                    <input type="text" name="maq1">
                                </div>
                                
                                <div id="maq2" class="campo form-group">
                                    <label>Máquina Utilizada (2):</label>
                                    <input type="text" name="maq2">
                                </div>
                                
                                <div id="maq3" class="campo form-group">
                                    <label>Máquina Utilizada (3):</label>
                                    <input type="text" name="maq3">
                                </div>
                                
                                <div id="maq4" class="campo form-group">
                                    <label>Máquina Utilizada (4):</label>
                                    <input type="text" name="maq4">
                                </div>
                                
                                <div id="tipo_cierre" class="campo form-group">
                                    <label>Tipo de Cierre:</label>
                                    <input type="text" name="tipo_cierre" value="Cierre de OP">
                                </div>
                                
                                <!-- Impresión -->
                                <div id="mp_entregada_impresion" class="campo form-group">
                                    <label>Materia Prima Entregada:</label>
                                    <input type="text" name="mp_entregada_impresion">
                                </div>
                                
                                <div id="cant_entregada_impresion" class="campo form-group">
                                    <label>Cantidad Entregada:</label>
                                    <input type="number" name="cant_entregada_impresion">
                                </div>
                                
                                <div id="maq_impresion" class="campo form-group">
                                    <label>Máquina Utilizada:</label>
                                    <input type="text" name="maq_impresion">
                                </div>
                                
                                <!-- Producto -->
                                <div id="producto" class="campo form-group">
                                    <label>Producto:</label>
                                    <input type="text" name="producto">
                                </div>
                                
                                <!-- Imagenes y Firma -->
                                <div id="imagenes" class="campo form-group">
                                    <label>Imágenes:</label>
                                    <input type="file" name="imagenes" multiple>
                                </div>
                                
                                <div id="firma" class="campo form-group">
                                    <label>Firma:</label>
                                    <input type="text" name="firma">
                                </div>
                                
                                <div id="comentarios" class="campo form-group">
                                    <label>Comentarios adicionales:</label>
                                    <textarea name="comentarios"></textarea>
                                </div>
                                
                                <!-- Campos adicionales creados dinámicamente -->
                                <div id="producto_listo_conformidad" class="campo form-group" style="display:none;">
                                    <label>¿El producto está listo para el próximo proceso?</label>
                                    <select name="producto_listo" id="producto_listo">
                                        <option value="">-- Seleccionar --</option>
                                        <option value="si">Sí</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                                
                                <div id="producto_conforme_div" class="campo form-group" style="display:none;">
                                    <label>¿El producto está conforme o rechazado?</label>
                                    <select name="producto_conforme" id="producto_conforme">
                                        <option value="">-- Seleccionar --</option>
                                        <option value="conforme">Conforme</option>
                                        <option value="rechazado">Rechazado</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="icon-container">
                            <i class="fas fa-industry"></i>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="form-footer">
                <button type="submit" form="formularioCampos" class="btn-submit">
                    <i class="fas fa-save"></i> Guardar Planificación
                </button>
            </div>
        </div>
    </div>

    <script>
        const usuariosRoles = <?php echo json_encode($usuariosRolesJS); ?>;
        
        // Definir las acciones disponibles para cada rol
        const accionesPorRol = {
            1: [
                { value: 'modificacion', text: 'Modificación de pedido MP' },
                { value: 'cierre', text: 'Cierre de OP' },
                { value: 'entrega', text: 'Entrega OP a control de gestión' }
            ],
            2: [
                { value: 'solicitud_mp', text: 'Solicitud de cambio de materia prima' },
                { value: 'entrega_mp', text: 'Entrega de materia prima' }
            ],
            3: [
                { value: 'recepcion_mp', text: 'Recepción materia prima' },
                { value: 'entrada_proceso', text: 'Entrada a proceso' },
                { value: 'salida_proceso', text: 'Salida de proceso' }
            ],
            4: [
                { value: 'recepcion_mp', text: 'Recepción materia prima' },
                { value: 'entrada_proceso', text: 'Entrada a proceso' },
                { value: 'salida_proceso', text: 'Salida de proceso' }
            ],
            5: [
                { value: 'conformidad_producto', text: 'Conformidad de producto' }
            ],
            6: [
                { value: 'control_producto', text: 'Control de producto' }
            ],
            7: [
                { value: 'despacho', text: 'Despacho' }
            ],
            8: [
                { value: 'recepcion_op', text: 'Recepción de OP' },
                { value: 'aprobacion_op', text: 'Aprobación de OP' }
            ]
        };

        function cargarAcciones(rolId) {
            const select = document.getElementById('accion_rol');
            // Limpiar opciones actuales
            select.innerHTML = '<option value="">-- Seleccionar acción --</option>';
            
            if (accionesPorRol[rolId]) {
                accionesPorRol[rolId].forEach(accion => {
                    const option = document.createElement('option');
                    option.value = accion.value;
                    option.textContent = accion.text;
                    select.appendChild(option);
                });
            }
        }

        function ocultarCampos() {
            document.querySelectorAll('.campo').forEach(c => {
                c.style.display = 'none';
            });
            document.getElementById('formularioCampos').reset();
        }

        function mostrarCampos() {
            ocultarCampos();

            const usuarioId = document.getElementById('usuario').value;
            const rolId = usuariosRoles[usuarioId];

            if (!usuarioId) return;

            document.getElementById('numero_op_contenedor').style.display = 'block';
            cargarAcciones(rolId);
        }

        function mostrarCamposPorAccion() {
            const accion = document.getElementById('accion_rol').value;
            const usuarioId = document.getElementById('usuario').value;
            const rolId = usuariosRoles[usuarioId];
            
            if (!accion) return;
            
            // Ocultar todos los campos primero
            ocultarCampos();
            
            // Mostrar campos comunes
            document.getElementById('cliente').style.display = 'block';
            document.getElementById('producto').style.display = 'block';
            document.getElementById('comentarios').style.display = 'block';
            
            // Mostrar campos específicos según la acción seleccionada
            switch(accion) {
                // Planificación
                case 'modificacion':
                    ['mp_solicitada', 'mp_sustituta', 'cant_programada', 'cant_solicitada', 'firma'].forEach(id => 
                        document.getElementById(id).style.display = 'block');
                    break;
                    
                case 'cierre':
                    ['mp_utilizada', 'cant_programada', 'cant_realizada', 'maq1', 'maq2', 'maq3', 'maq4', 'tipo_cierre', 'imagenes', 'firma'].forEach(id => 
                        document.getElementById(id).style.display = 'block');
                    break;
                    
                case 'entrega':
                    ['imagenes', 'firma'].forEach(id => 
                        document.getElementById(id).style.display = 'block');
                    break;
                    
                // Bodega
                case 'solicitud_mp':
                    ['mp_solicitada', 'mp_sustituta', 'cant_programada', 'cant_entregada', 'razon_solicitud', 'imagenes', 'firma'].forEach(id => 
                        document.getElementById(id).style.display = 'block');
                    break;
                    
                case 'entrega_mp':
                    ['mp_solicitada', 'cant_solicitada', 'cant_entregada', 'entregado_a', 'mp_sustituta', 'imagenes', 'firma'].forEach(id => 
                        document.getElementById(id).style.display = 'block');
                    break;
                    
                // Impresión
                case 'recepcion_mp':
                    ['mp_entregada_impresion', 'cant_entregada_impresion', 'imagenes', 'firma'].forEach(id => 
                        document.getElementById(id).style.display = 'block');
                    break;
                    
                case 'entrada_proceso':
                    ['mp_utilizada', 'cant_programada', 'maq_impresion', 'imagenes', 'firma'].forEach(id => 
                        document.getElementById(id).style.display = 'block');
                    break;
                    
                case 'salida_proceso':
                    ['mp_utilizada', 'cant_programada', 'cant_realizada', 'maq_impresion', 'imagenes', 'firma'].forEach(id => 
                        document.getElementById(id).style.display = 'block');
                    break;
                    
                // Corte/Corrugado
                case 'recepcion_mp_corte':
                    ['mp_entregada_impresion', 'cant_entregada_impresion', 'firma'].forEach(id => 
                        document.getElementById(id).style.display = 'block');
                    break;
                    
                case 'entrada_proceso_corte':
                    ['mp_utilizada', 'cant_programada', 'maq_impresion'].forEach(id => 
                        document.getElementById(id).style.display = 'block');
                    break;
                    
                case 'salida_proceso_corte':
                    ['mp_utilizada', 'cant_programada', 'cant_realizada', 'maq_impresion'].forEach(id => 
                        document.getElementById(id).style.display = 'block');
                    break;
                    
                // Supervisor
                case 'conformidad_producto':
                    ['mp_utilizada', 'cant_realizada', 'imagenes', 'firma', 'producto_listo_conformidad'].forEach(id => 
                        document.getElementById(id).style.display = 'block');
                    break;
                    
                // Calidad
                case 'control_producto':
                    ['mp_utilizada', 'cant_realizada', 'maq1', 'imagenes', 'firma', 'producto_conforme_div'].forEach(id => 
                        document.getElementById(id).style.display = 'block');
                    break;
                    
                // Despacho
                case 'despacho':
                    ['mp_utilizada', 'cant_realizada', 'imagenes'].forEach(id => 
                        document.getElementById(id).style.display = 'block');
                    break;
                    
                // Control de Gestión
                case 'recepcion_op':
                    ['mp_utilizada', 'imagenes', 'firma'].forEach(id => 
                        document.getElementById(id).style.display = 'block');
                    break;
                    
                case 'aprobacion_op':
                    ['mp_utilizada', 'cant_programada', 'cant_realizada', 'maq1', 'maq2', 'maq3', 'maq4', 'imagenes', 'firma'].forEach(id => 
                        document.getElementById(id).style.display = 'block');
                    break;
            }
        }

        // Función para sincronizar el select con el input hidden
        function sincronizarUsuario() {
            var usuarioSelect = document.getElementById('usuario');
            var inputOculto = document.getElementById('usuario_id_oculto');
            inputOculto.value = usuarioSelect.value;
        }
        
        // Mostrar indicador de dispositivo en desarrollo
        document.querySelector('.device-indicator').style.display = 'block';
    </script>
</body>
</html>