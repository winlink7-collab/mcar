<?php
require_once 'includes/header.php';
?>

<main class="page-enter container" style="padding: 120px 0; text-align: center;">
    <div style="font-family: var(--font-display); font-size: 120px; font-weight: 900; color: var(--accent); opacity: 0.2; line-height: 1;">404</div>
    <h1 style="font-size: 48px; font-weight: 900; margin-top: -20px; margin-bottom: 24px;">הגעת למבוי סתום...</h1>
    <p style="color: var(--ink-3); font-size: 18px; max-width: 50ch; margin: 0 auto 40px;">העמוד שחיפשת לא קיים או שהועבר למסלול אחר. אל דאגה, השארנו לך מפה חזרה לקטלוג.</p>
    
    <div style="display: flex; gap: 16px; justify-content: center;">
        <a href="index.php" class="btn btn-primary btn-lg">דף הבית</a>
        <a href="grid.php" class="btn btn-ghost btn-lg">קטלוג רכבים</a>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
