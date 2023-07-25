<form id="authForm" action="/authenticate" method="post">
    <input type="hidden" name="username" value="<?= $userName ?>">
    <input type="hidden" name="password" value="<?= $userPassword ?>">
</form>

<script>
    document.getElementById('authForm').submit();
</script>
