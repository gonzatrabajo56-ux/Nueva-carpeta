<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1cm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            font-size: 9px;
            color: #333;
            background: #fff;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #2c3e50;
        }
        
        .header h1 {
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .header .subtitle {
            font-size: 12px;
            color: #7f8c8d;
            margin-bottom: 10px;
        }
        
        .header .fecha {
            font-size: 10px;
            color: #95a5a6;
            background: #ecf0f1;
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
        }
        
        .company-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px;
        }
        
        .company-info h2 {
            font-size: 18px;
        }
        
        .company-info p {
                <td><?= $p['siglas_universidad'] ?? '' ?></td>
                <td><?= $p['municipio'] ?? '' ?></td>
                <td><?= $p['cantidad_hijos'] ?? '' ?></td>
                <td><?= $p['posee_discapacidad'] ?? '' ?></td>
                <td><?= $p['trabaja'] ?? '' ?></td>
                <td><?= $p['tipo_sangre'] ?? '' ?></td>
                <td><?= $p['estado_civil'] ?? '' ?></td>
                <td><?= date('d/m/Y', strtotime($p['fecha_registro'] ?? 'now')) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p class="total">Total: <?= count($personas) ?> personas</p>
</body>
</html>
