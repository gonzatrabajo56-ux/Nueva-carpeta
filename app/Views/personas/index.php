<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-people"></i> Personas</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/personas/create" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-circle"></i> Nueva Persona
        </a>
    </div>
</div>

<!-- Buscador -->
<form method="GET" action="/personas" class="mb-4">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Buscar por nombre, apellido o cédula..." value="<?= htmlspecialchars($search ?? '') ?>">
        <button type="submit" class="btn btn-outline-secondary">
            <i class="bi bi-search"></i> Buscar
        </button>
        <?php if (!empty($search)): ?>
            <a href="/personas" class="btn btn-outline-danger">Limpiar</a>
        <?php endif; ?>
    </div>
</form>

<!-- Tabla de personas -->
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Cédula</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Sexo</th>
                <th>Edad</th>
                <th>Teléfono</th>
                <th>Universidad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($personas)): ?>
                <tr>
                    <td colspan="9" class="text-center">No hay registros</td>
                </tr>
            <?php else: ?>
                <?php foreach ($personas as $p): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td><?= htmlspecialchars($p['cedula'] ?? '') ?></td>
                        <td><?= htmlspecialchars(($p['primer_nombre'] ?? '') . ' ' . ($p['segundo_nombre'] ?? '')) ?></td>
                        <td><?= htmlspecialchars(($p['primer_apellido'] ?? '') . ' ' . ($p['segundo_apellido'] ?? '')) ?></td>
                        <td><?= htmlspecialchars($p['sexo'] ?? '') ?></td>
                        <td><?= $p['edad'] ?? '-' ?></td>
                        <td><?= htmlspecialchars($p['telefono_1'] ?? '') ?></td>
                        <td><?= htmlspecialchars($p['siglas_universidad'] ?? '') ?></td>
                        <td>
                            <a href="/personas/show/<?= $p['id'] ?>" class="btn btn-sm btn-info" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/personas/edit/<?= $p['id'] ?>" class="btn btn-sm btn-warning" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="/personas/delete/<?= $p['id'] ?>" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Está seguro de eliminar este registro?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Paginación -->
<?php if (isset($totalPages) && $totalPages > 1): ?>
    <nav>
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>
