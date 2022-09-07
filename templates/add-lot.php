<?php
/** @var array $categories */
/** @var array $errors */
/** @var array $formData */

?>
<nav class="nav">
      <ul class="nav__list container">
          <?php foreach ($categories as $category): ?>
        <li class="nav__item">
          <a href="../pages/all-lots.html"><?= htmlspecialchars($category['name']) ?></a>
        </li>
          <?php endforeach; ?>
      </ul>
    </nav>
    <form class="form form--add-lot container form--invalid" action="../add.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
      <h2>Добавление лота</h2>
      <div class="form__container-two">
        <div class="form__item <?= isset($errors['title']) ? 'form__item--invalid': ''?>"> <!-- form__item--invalid -->
          <label for="lot-name">Наименование <sup>*</sup></label>
          <input id="lot-name" type="text" name="title" placeholder="Введите наименование лота" value="<?= $formData['title'] ?? ''?>">
          <span class="form__error"><?= $errors['title'] ?? '' ?></span>
        </div>
        <div class="form__item">
          <label for="category">Категория <sup>*</sup></label>

          <select id="category" name="name">

            <option>Выберите категорию</option>
              <?php foreach ($categories as $category): ?>
            <option <?php if($category['name'] === $formData['name']): ?>selected<?php endif; ?> value="<?= $category['name'] ?>"><?= $category['name'] ?></option>
              <?php endforeach; ?>
          </select>
          <span class="form__error">Выберите категорию</span>
        </div>
      </div>
      <div class="form__item form__item--wide">
        <label for="message">Описание <sup>*</sup></label>
        <textarea id="message" name="description" placeholder="Напишите описание лота" ><?= $formData['description'] ?? ''?></textarea>
        <span class="form__error">Напишите описание лота</span>
      </div>
      <div class="form__item form__item--file">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
          <input class="visually-hidden" type="file" id="lot-img" name="image" value="">
          <label for="lot-img">
Добавить
          </label>
        </div>
      </div>
      <div class="form__container-three">
        <div class="form__item form__item--small">
          <label for="lot-rate">Начальная цена <sup>*</sup></label>
          <input id="lot-rate" type="text" name="price" placeholder="0">
          <span class="form__error">Введите начальную цену</span>
        </div>
        <div class="form__item form__item--small">
          <label for="lot-step">Шаг ставки <sup>*</sup></label>
          <input id="lot-step" type="text" name="step" placeholder="0">
          <span class="form__error">Введите шаг ставки</span>
        </div>
        <div class="form__item">
          <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
          <input class="form__input-date" id="lot-date" type="text" name="finish_date" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
          <span class="form__error">Введите дату завершения торгов</span>
        </div>
      </div>
      <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
      <button type="submit" class="button">Добавить лот</button>
    </form>
