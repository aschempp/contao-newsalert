<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<form action="<?php echo $this->action; ?>" method="post">
<div class="formbody"><?php if ($this->message): ?> 
<p class="<?php echo $this->mclass; ?>"><?php echo $this->message; ?></p><?php endif; ?> 
<input type="hidden" name="FORM_SUBMIT" value="<?php echo $this->formId; ?>" />
E-Mail: <input type="text" name="email" class="text" value="<?php echo $this->email; ?>" />
<?php if( count($this->archives) == 1 ): $id=current(array_keys($this->archives)); ?>
<input type="hidden" name="archive[]" class="hidden" value="<?php echo $id; ?>" /> <input type="submit" name="submit" class="submit" value="<?php echo $this->submit; ?>" />
<?php else: ?>
<?php foreach( $this->archives as $id => $name ): ?>
<br /><input type="checkbox" name="archive[]" class="checkbox" value="<?php echo $id; ?>" /><label for="archive"><?php echo $name; ?></label>
<?php endforeach; ?>
<br /><input type="submit" name="submit" class="submit" value="<?php echo $this->submit; ?>" />
<?php endif; ?>
</div>
</form>

</div>
