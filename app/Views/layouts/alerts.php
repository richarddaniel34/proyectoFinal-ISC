<?php if (session()->getFlashdata('success')): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: '<?= session()->getFlashdata('success'); ?>'
        });
    </script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '<?= session()->getFlashdata('error'); ?>'
        });
    </script>
<?php endif; ?>

<?php if (session()->getFlashdata('warning')): ?>
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Advertencia',
            text: '<?= session()->getFlashdata('warning'); ?>'
        });
    </script>
<?php endif; ?>