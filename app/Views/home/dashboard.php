<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="py-4">
    <h2 class="mb-4">Dashboard</h2>
    
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card card-stat bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Total Personas</h6>
                            <h2 class="mb-0"><?= $stats['personas_total'] ?? 0 ?></h2>
                        </div>
                        <i class="bi bi-people fs-1 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="<?= base_url('/personas') ?>" class="text-white text-decoration-none">
                        Ver todas <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card card-stat bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Personas Activas</h6>
                            <h2 class="mb-0"><?= $stats['personas_activas'] ?? 0 ?></h2>
                        </div>
                        <i class="bi bi-person-check fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card card-stat bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Evaluaciones</h6>
                            <h2 class="mb-0"><?= $stats['evaluaciones'] ?? 0 ?></h2>
                        </div>
                        <i class="bi bi-clipboard-check fs-1 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="<?= base_url('/evaluaciones') ?>" class="text-white text-decoration-none">
                        Ver todas <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card card-stat bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Seguimientos</h6>
                            <h2 class="mb-0"><?= $stats['seguimientos'] ?? 0 ?></h2>
                        </div>
                        <i class="bi bi-journal-text fs-1 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="<?= base_url('/seguimientos') ?>" class="text-white text-decoration-none">
                        Ver todos <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-exclamation-circle"></i> Pendientes</h5>
                </div>
                <div class="card-body">
                    <h3 class="text-warning"><?= $stats['pendientes'] ?? 0 ?></h3>
                    <p class="text-muted">Seguimientos pendientes</p>
                    <a href="<?= base_url('/seguimientos/pendientes') ?>" class="btn btn-warning btn-sm">
                        Ver pendientes
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Próximos</h5>
                </div>
                <div class="card-body">
                    <h3 class="text-info"><?= $stats['proximos'] ?? 0 ?></h3>
                    <p class="text-muted">Seguimientos en los próximos 7 días</p>
                    <a href="<?= base_url('/seguimientos/proximos') ?>" class="btn btn-info btn-sm">
                        Ver próximos
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Seguimientos Recientes -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Seguimientos Recientes</h5>
        </div>
        <div class="card-body">
            <?php if (empty($seguimientos_recientes)): ?>
                <p class="text-muted">No hay seguimientos recientes</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Estado</th>
                                <th>Prioridad</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($seguimientos_recientes as $seg): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($seg['fecha_seguimiento'])) ?></td>
                                <td><?= $seg['tipo_seguimiento'] ?></td>
                                <td>
                                    <span class="badge bg-<?= $seg['estado'] == 'COMPLETADO' ? 'success' : ($seg['estado'] == 'PENDIENTE' ? 'warning' : 'primary') ?>">
                                        <?= $seg['estado'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $seg['prioridad'] == 'URGENTE' ? 'danger' : ($seg['prioridad'] == 'ALTA' ? 'warning' : 'secondary') ?>">
                                        <?= $seg['prioridad'] ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= base_url('/seguimientos/show/' . $seg['id']) ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
