<h2 class="header-task">Панель администратора<span class="task-number" hidden="hidden">1</span></h2>
<div class="container-task container container-admin">
    <input id="inputSearch" class="input-search input-form" placeholder="Поле поиска пользователей по фамилии">
    <div id="content-table"><?php if (!empty($this->arguments['resultTask'])) : ?><?php
            foreach ($this->arguments['resultTask'] as $value) :
                $i = 1;
                $countMarks = 0
                ?>
                <article>
                    <h3>
                        Участник: <span id="login-user"
                                        class="login-text changeable-text"><?= $value["firstName"] ?> <?= $value["lastName"] ?> <?= $value["middleName"] ?></span>
                    </h3>
                    <h3 class="login-container">Логин
                        <?= $value["login"] ?></h3>
                    <table class="table-admin">
                        <tr>
                            <th>№</th>
                            <th>Задание</th>
                            <th>Кол-во баллов</th>
                            <th>Выполнено</th>
                        </tr>

                        <tr class="<?php if ($value["add_client:done"] === "false") : ?>tr-failed-user<?php endif ?>">
                            <td><?= $i++ ?></td>
                            <td><?= $value["add_client:title"] ?>
                                <?php if (isset($value["add_client:meaning"])) : ?>
                                    (<?= $value["add_client:meaning"] ?>)
                                <?php endif; ?></td>
                            <td><?= $value["add_client:marks"] ?></td>
                            <td><?= $value["add_client:done"] ?></td>
                        </tr>
                        <?php $countMarks += (float)$value["add_client:marks"]; ?>


                        <tr <?php if ($value["alias:done"] === "false") : ?>class="tr-failed-user" <?php else : ?> class="" <?php endif ?>>
                            <td><?= $i++ ?></td>
                            <td><?= $value["alias:title"] ?>
                                <?php if (isset($value["alias:meaning"])) : ?>
                                    (<?= $value["alias:meaning"] ?>)
                                <?php endif; ?></td>
                            <td><?= $value["alias:marks"] ?></td>
                            <td><?= $value["alias:done"] ?></td>
                        </tr>
                        <?php $countMarks += (float)$value["alias:marks"]; ?>

                        <tr <?php if ($value["gender:done"] === "false") : ?>class="tr-failed-user" <?php else : ?> class="" <?php endif ?>>
                            <td><?= $i++ ?></td>
                            <td><?= $value["gender:title"] ?>
                                <?php if (isset($value["gender:meaning"])) : ?>
                                    (<?= $value["gender:meaning"] ?>)
                                <?php endif; ?></td>
                            <td><?= $value["gender:marks"] ?></td>
                            <td><?= $value["gender:done"] ?></td>
                        </tr>
                        <?php $countMarks += (float)$value["gender:marks"]; ?>

                        <tr <?php if ($value["dateOfBirth:done"] === "false") : ?>class="tr-failed-user" <?php else : ?> class="" <?php endif ?>>
                            <td><?= $i++ ?></td>
                            <td><?= $value["dateOfBirth:title"] ?>
                                <?php if (isset($value["dateOfBirth:meaning"])) : ?>
                                    (<?= $value["dateOfBirth:meaning"] ?>)
                                <?php endif; ?></td>
                            <td><?= $value["dateOfBirth:marks"] ?></td>
                            <td><?= $value["dateOfBirth:done"] ?></td>
                        </tr>
                        <?php $countMarks += (float)$value["dateOfBirth:marks"]; ?>

                        <tr <?php if ($value["breed:done"] === "false") : ?>class="tr-failed-user" <?php else : ?> class="" <?php endif ?>>
                            <td><?= $i++ ?></td>
                            <td><?= $value["breed:title"] ?>
                                <?php if (isset($value["breed:meaning"])) : ?>
                                    (<?= $value["breed:meaning"] ?>)
                                <?php endif; ?></td>
                            <td><?= $value["breed:marks"] ?></td>
                            <td><?= $value["breed:done"] ?></td>
                        </tr>
                        <?php $countMarks += (float)$value["breed:marks"]; ?>

                        <tr <?php if ($value["color:done"] === "false") : ?>class="tr-failed-user" <?php else : ?> class="" <?php endif ?>>
                            <td><?= $i++ ?></td>
                            <td><?= $value["color:title"] ?>
                                <?php if (isset($value["color:meaning"])) : ?>
                                    (<?= $value["color:meaning"] ?>)
                                <?php endif; ?></td>
                            <td><?= $value["color:marks"] ?></td>
                            <td><?= $value["color:done"] ?></td>
                        </tr>
                        <?php $countMarks += (float)$value["color:marks"]; ?>

                        <tr <?php if ($value["type:done"] === "false") : ?>class="tr-failed-user" <?php else : ?> class="" <?php endif ?>>
                            <td><?= $i++ ?></td>
                            <td><?= $value["type:title"] ?>
                                <?php if (isset($value["type:meaning"])) : ?>
                                    (<?= $value["type:meaning"] ?>)
                                <?php endif; ?></td>
                            <td><?= $value["type:marks"] ?></td>
                            <td><?= $value["type:done"] ?></td>
                        </tr>
                        <?php $countMarks += (float)$value["type:marks"]; ?>

                        <tr <?php if ($value["purpose_appointment:done"] === "false") : ?>class="tr-failed-user" <?php else : ?> class="" <?php endif ?>>
                            <td><?= $i++ ?></td>
                            <td><?= $value["purpose_appointment:title"] ?>
                                <?php if (isset($value["purpose_appointment:meaning"])) : ?>
                                    (<?= $value["purpose_appointment:meaning"] ?>)
                                <?php endif; ?></td>
                            <td><?= $value["purpose_appointment:marks"] ?></td>
                            <td><?= $value["purpose_appointment:done"] ?></td>
                        </tr>
                        <?php $countMarks += (float)$value["purpose_appointment:marks"]; ?>

                        <tr <?php if ($value["text_template:done"] === "false") : ?>class="tr-failed-user" <?php else : ?> class="" <?php endif ?>>
                            <td><?= $i++ ?></td>
                            <td><?= $value["text_template:title"] ?>
                                <?php if (isset($value["text_template:meaning"])) : ?>
                                    (<?= $value["text_template:meaning"] ?>)
                                <?php endif; ?></td>
                            <td><?= $value["text_template:marks"] ?></td>
                            <td><?= $value["text_template:done"] ?></td>
                        </tr>
                        <?php $countMarks += (float)$value["text_template:marks"]; ?>

                        <tr <?php if ($value["result_appointment:done"] === "false") : ?>class="tr-failed-user" <?php else : ?> class="" <?php endif ?>>
                            <td><?= $i++ ?></td>
                            <td><?= $value["result_appointment:title"] ?>
                                <?php if (isset($value["result_appointment:meaning"])) : ?>
                                    (<?= $value["result_appointment:meaning"] ?>)
                                <?php endif; ?></td>
                            <td><?= $value["result_appointment:marks"] ?></td>
                            <td><?= $value["result_appointment:done"] ?></td>
                        </tr>
                        <?php $countMarks += (float)$value["result_appointment:marks"]; ?>

                        <tr <?php if ($value["animal_diagnosis:done"] === "false") : ?>class="tr-failed-user" <?php else : ?> class="" <?php endif ?>>
                            <td><?= $i++ ?></td>
                            <td><?= $value["animal_diagnosis:title"] ?>
                                <?php if (isset($value["animal_diagnosis:meaning"])) : ?>
                                    (<?= $value["animal_diagnosis:meaning"] ?>)
                                <?php endif; ?></td>
                            <td><?= $value["animal_diagnosis:marks"] ?></td>
                            <td><?= $value["animal_diagnosis:done"] ?></td>
                        </tr>
                        <?php $countMarks += (float)$value["animal_diagnosis:marks"]; ?>

                        <tr <?php if ($value["type_animal_diagnosis:done"] === "false") : ?>class="tr-failed-user" <?php else : ?> class="" <?php endif ?>>
                            <td><?= $i++ ?></td>
                            <td><?= $value["type_animal_diagnosis:title"] ?>
                                <?php if (isset($value["type_animal_diagnosis:meaning"])) : ?>
                                    (<?= $value["type_animal_diagnosis:meaning"] ?>)
                                <?php endif; ?></td>
                            <td><?= $value["type_animal_diagnosis:marks"] ?></td>
                            <td><?= $value["type_animal_diagnosis:done"] ?></td>
                        </tr>
                        <?php $countMarks += (float)$value["type_animal_diagnosis:marks"]; ?>

                        <tr <?php if ($value["appointment_invoice:done"] === "false") : ?>class="tr-failed-user" <?php else : ?> class="" <?php endif ?>>
                            <td><?= $i++ ?></td>
                            <td><?= $value["appointment_invoice:title"] ?>
                                <?php if (isset($value["appointment_invoice:meaning"])) : ?>
                                    (<?= $value["appointment_invoice:meaning"] ?>)
                                <?php endif; ?></td>
                            <td><?= $value["appointment_invoice:marks"] ?></td>
                            <td><?= $value["appointment_invoice:done"] ?></td>
                        </tr>
                        <?php $countMarks += (float)$value["appointment_invoice:marks"]; ?>

                        <tr <?php if ($value["opening_of_abscess:done"] === "false") : ?>class="tr-failed-user" <?php else : ?> class="" <?php endif ?>>
                            <td><?= $i++ ?></td>
                            <td><?= $value["opening_of_abscess:title"] ?>
                                <?php if (isset($value["opening_of_abscess:meaning"])) : ?>
                                    (<?= $value["opening_of_abscess:meaning"] ?>)
                                <?php endif; ?></td>
                            <td><?= $value["opening_of_abscess:marks"] ?></td>
                            <td><?= $value["opening_of_abscess:done"] ?></td>
                        </tr>
                        <?php $countMarks += (float)$value["opening_of_abscess:marks"]; ?>

                        <tr <?php if ($value["sanitation_of_wound:done"] === "false") : ?>class="tr-failed-user" <?php else : ?> class="" <?php endif ?>>
                            <td><?= $i++ ?></td>
                            <td><?= $value["sanitation_of_wound:title"] ?>
                                <?php if (isset($value["sanitation_of_wound:meaning"])) : ?>
                                    (<?= $value["sanitation_of_wound:meaning"] ?>)
                                <?php endif; ?></td>
                            <td><?= $value["sanitation_of_wound:marks"] ?></td>
                            <td><?= $value["sanitation_of_wound:done"] ?></td>
                        </tr>
                        <?php $countMarks += (float)$value["sanitation_of_wound:marks"]; ?>

                        <tr <?php if ($value["injection_analgesic_antipyretic:done"] === "false") : ?>class="tr-failed-user" <?php else : ?> class="" <?php endif ?>>
                            <td><?= $i++ ?></td>
                            <td><?= $value["injection_analgesic_antipyretic:title"] ?>
                                <?php if (isset($value["injection_analgesic_antipyretic:meaning"])) : ?>
                                    (<?= $value["injection_analgesic_antipyretic:meaning"] ?>)
                                <?php endif; ?></td>
                            <td><?= $value["injection_analgesic_antipyretic:marks"] ?></td>
                            <td><?= $value["injection_analgesic_antipyretic:done"] ?></td>
                        </tr>
                        <?php $countMarks += (float)$value["injection_analgesic_antipyretic:marks"]; ?>

                        <tr <?php if ($value["payment_type:done"] === "false") : ?>class="tr-failed-user" <?php else : ?> class="" <?php endif ?>>
                            <td><?= $i++ ?></td>
                            <td><?= $value["payment_type:title"] ?>
                                <?php if (isset($value["payment_type:meaning"])) : ?>
                                    (<?= $value["payment_type:meaning"] ?>)
                                <?php endif; ?></td>
                            <td><?= $value["payment_type:marks"] ?></td>
                            <td><?= $value["payment_type:done"] ?></td>
                        </tr>
                        <?php $countMarks += (float)$value["payment_type:marks"]; ?>

                        <tr <?php if ($value["add_coupon:done"] === "false") : ?>class="tr-failed-user" <?php else : ?> class="" <?php endif ?>>
                            <td><?= $i++ ?></td>
                            <td><?= $value["add_coupon:title"] ?>
                                <?php if (isset($value["add_coupon:meaning"])) : ?>
                                    (<?= $value["add_coupon:meaning"] ?>)
                                <?php endif; ?></td>
                            <td><?= $value["add_coupon:marks"] ?></td>
                            <td><?= $value["add_coupon:done"] ?></td>
                        </tr>
                        <?php $countMarks += (float)$value["add_coupon:marks"]; ?>

                        <tr <?php if ($value["add_repeat_appointment:done"] === "false") : ?>class="tr-failed-user" <?php else : ?> class="" <?php endif ?>>
                            <td><?= $i++ ?></td>
                            <td><?= $value["add_repeat_appointment:title"] ?>
                                <?php if (isset($value["add_repeat_appointment:meaning"])) : ?>
                                    (<?= $value["add_repeat_appointment:meaning"] ?>)
                                <?php endif; ?></td>
                            <td><?= $value["add_repeat_appointment:marks"] ?></td>
                            <td><?= $value["add_repeat_appointment:done"] ?></td>
                        </tr>
                        <?php $countMarks += (float)$value["add_repeat_appointment:marks"]; ?>
                    </table>
                    <h3>Общее количество баллов: <?= $countMarks ?> </h3>
                    <hr>
                </article>
            <?php endforeach ?>
        <?php endif ?>
    </div>
</div>
