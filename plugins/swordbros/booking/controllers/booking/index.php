<div class="toolbar-widget list-header">
<h3><?= $this->pageTitle ?></h3>
</div>
<?= $this->listRender() ?>
<script>
    $(document).ready(function() {
        $('.btn-send-email').click(function(event) {
            window.open($(this).attr('href'), '_blank');
            return false;
        });
    });

</script>
