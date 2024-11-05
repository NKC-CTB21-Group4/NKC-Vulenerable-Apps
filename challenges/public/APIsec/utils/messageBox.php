<script>
    window.onload = function() {
        var toast = document.getElementById('toast');
        toast.style.display = 'block';
        setTimeout(function() {
            toast.style.display = 'none';
        }, 5000); // 5秒後にメッセージを消す
    }
</script>