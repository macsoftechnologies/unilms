<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Quiz Builder<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div style="margin-bottom: 24px;">
    <a href="<?= base_url('org/quizzes') ?>" style="color: var(--primary); text-decoration: none; font-size: 14px;"><i class="fa-solid fa-arrow-left"></i> Back to Quizzes</a>
</div>

<div class="header-banner">
    <div style="display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h1 class="header-title">Builder: <?= esc($quiz['title']) ?></h1>
            <p class="header-subtitle">Add Multiple Choice Questions to your quiz.</p>
        </div>
        <div>
            <?php if($quiz['is_published']): ?>
                <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--success); font-size: 14px; padding: 8px 16px;">Status: Published</span>
            <?php else: ?>
                <span class="badge" style="background: rgba(0,0,0,0.05); color: var(--text-muted); font-size: 14px; padding: 8px 16px;">Status: Draft</span>
            <?php endif; ?>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
    
    <!-- Question List -->
    <div>
        <?php if(empty($questions)): ?>
            <div class="card" style="text-align: center; padding: 40px; color: var(--text-muted);">
                <i class="fa-solid fa-clipboard-question" style="font-size: 48px; opacity: 0.5; margin-bottom: 16px;"></i>
                <p>No questions added yet. Use the form on the right to add your first question.</p>
            </div>
        <?php else: ?>
            <?php foreach($questions as $idx => $q): ?>
                <div class="card" style="margin-bottom: 16px; position: relative;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                        <h3 style="margin: 0; font-size: 16px;">Q<?= $idx + 1 ?>. <?= esc($q['question_text']) ?></h3>
                        <span class="badge" style="background: var(--bg-light); color: var(--text-muted);"><?= $q['marks'] ?> marks</span>
                    </div>
                    
                    <div style="padding-left: 16px; display: flex; flex-direction: column; gap: 8px;">
                        <?php foreach($q['options'] as $opt): ?>
                            <div style="display: flex; align-items: center; gap: 12px; font-size: 14px;">
                                <?php if($opt['is_correct']): ?>
                                    <i class="fa-solid fa-circle-check" style="color: var(--success);"></i>
                                    <span style="font-weight: 600; color: var(--success);"><?= esc($opt['option_text']) ?></span>
                                <?php else: ?>
                                    <i class="fa-regular fa-circle" style="color: var(--text-muted);"></i>
                                    <span style="color: var(--text-primary);"><?= esc($opt['option_text']) ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <form action="<?= base_url('org/quizzes/delete_question/' . $q['id']) ?>" method="POST" style="position: absolute; bottom: 16px; right: 16px;" onsubmit="return confirm('Remove this question?');">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-outline" style="padding: 4px 10px; font-size: 12px; color: var(--danger); border-color: transparent;"><i class="fa-solid fa-trash"></i> Remove</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Add Question Form -->
    <div>
        <div class="card" style="position: sticky; top: 24px;">
            <h2 style="margin-top: 0; margin-bottom: 20px; font-size: 18px;">Add New Question</h2>
            
            <form action="<?= base_url('org/quizzes/save_question') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="quiz_id" value="<?= $quiz['id'] ?>">
                
                <div class="form-group">
                    <label>Question Text</label>
                    <textarea name="question_text" class="form-control" rows="3" required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Marks</label>
                    <input type="number" min="0" step="0.5" name="marks" class="form-control" value="1" required>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 8px;">Options & Correct Answer</label>
                    
                    <?php for($i=0; $i<4; $i++): ?>
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                            <input type="radio" name="correct_option" value="<?= $i ?>" <?= $i==0 ? 'checked' : '' ?> required style="width: 18px; height: 18px; cursor: pointer;">
                            <input type="text" name="options[<?= $i ?>]" class="form-control" placeholder="Option <?= $i+1 ?>" required>
                        </div>
                    <?php endfor; ?>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="fa-solid fa-plus"></i> Add Question</button>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

