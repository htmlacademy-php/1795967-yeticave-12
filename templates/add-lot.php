<?php
/** @var array $categories
 * @var array $errors
 * @var array $formData
 */

?>
    <form class="form form--add-lot container <?= (!empty($errors)) ? 'form--invalid' : '' ?>" action="../add.php"
          method="post"
          enctype="multipart/form-data"> <!-- form--invalid -->
        <h2>Добавление лота</h2>
        <div class="form__container-two">
            <div class="form__item <?= isset($errors['title']) ? 'form__item--invalid' : '' ?>">
                <!-- form__item--invalid -->
                <label for="lot-name">Наименование <sup>*</sup></label>
                <input id="lot-name" type="text" name="title" placeholder="Введите наименование лота"
                       value="<?= $formData['title'] ?? '' ?>">
                <span class="form__error"><?= $errors['title'] ?? '' ?></span>
            </div>
            <div class="form__item <?= isset($errors['category_id']) ? 'form__item--invalid' : '' ?>">
                <label for="category">Категория <sup>*</sup></label>

                <select id="category" name="category_id">

                    <option value="">Выберите категорию</option>
                    <?php
                    foreach ($categories as $category): ?>
                        <option <?= ($formData && ($formData['category_id'] === (int)$category['id'])) ? 'selected' : '' ?>
                            value="<?= $category['id'] ?? '' ?>">
                            <?= $category['name'] ?>
                        </option>
                    <?php
                    endforeach; ?>
                </select>
                <span class="form__error"><?= $errors['category_id'] ?? '' ?></span>
            </div>
        </div>
        <div class="form__item form__item--wide <?= isset($errors['description']) ? 'form__item--invalid' : '' ?>">
            <label for="message">Описание <sup>*</sup></label>
            <textarea id="message" name="description"
                      placeholder="Добавьте описание лота"><?= $formData['description'] ?? '' ?></textarea>
            <span class="form__error"><?= $errors['description'] ?? '' ?></span>
        </div>
        <div class="form__item form__item--file <?= isset($errors['image']) ? 'form__item--invalid' : '' ?>">
            <label>Изображение <sup>*</sup></label>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" id="lot-img" name="image" value="">
                <label for="lot-img">
                    Добавить
                </label>
            </div>
            <span class="form__error"><?= $errors['image'] ?? '' ?></span>
        </div>
        <div class="form__container-three">
            <div class="form__item form__item--small <?= isset($errors['price']) ? 'form__item--invalid' : '' ?>">
                <label for="lot-rate">Начальная цена <sup>*</sup></label>
                <input id="lot-rate" type="text" name="price" placeholder="0" value="<?= $formData['price'] ?? '' ?>">
                <span class="form__error"><?= $errors['price'] ?? '' ?>>></span>
            </div>
            <div class="form__item form__item--small <?= isset($errors['step']) ? 'form__item--invalid' : '' ?>">
                <label for="lot-step">Шаг ставки <sup>*</sup></label>
                <input id="lot-step" type="text" name="step" placeholder="0" value="<?= $formData['step'] ?? '' ?>">
                <span class="form__error"><?= $errors['step'] ?? '' ?>></span>
            </div>
            <div class="form__item <?= isset($errors['finish_date']) ? 'form__item--invalid' : '' ?>">
                <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
                <input class="form__input-date" id="lot-date" type="text" name="finish_date"
                       placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?= $formData['finish_date'] ?? '' ?>">
                <span class="form__error"><?= $errors['finish_date'] ?? '' ?>></span>
            </div>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Добавить лот</button>
    </form>
