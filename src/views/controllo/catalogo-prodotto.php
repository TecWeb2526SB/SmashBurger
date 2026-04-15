<?php
/**
 * controllo-catalogo-prodotto: View flusso guidato catalogo globale.
 *
 * Variabili attese:
 *   $existingProduct ?array
 *   $currentStep string
 *   $steps array
 *   $draft array
 *   $categories array
 *   $flash ?array
 *   $csrfToken string
 */

$stepLabels = [
    'dettagli' => 'Dati prodotto',
    'immagine' => 'Immagine e inquadratura',
    'riepilogo' => 'Riepilogo e conferma',
];

$categoryNameById = [];
foreach ($categories as $category) {
    $categoryNameById[(int) $category['id']] = (string) $category['name'];
}

$currentTitle = $existingProduct !== null ? 'Modifica prodotto globale' : 'Nuovo prodotto globale';
$isReviewStep = $currentStep === 'riepilogo';
?>

<section class="account-page admin-page" aria-labelledby="titolo-catalogo-prodotto">
    <div class="contenitore">
        <div class="account-page-head">
            <h1 id="titolo-catalogo-prodotto"><?php echo htmlspecialchars($currentTitle, ENT_QUOTES, 'UTF-8'); ?></h1>
            <div class="account-action-row">
                <a class="bottone-secondario" href="controllo-catalogo">&larr; Torna al catalogo</a>
                <a class="bottone-secondario" href="controllo-catalogo-prodotto<?php echo $existingProduct !== null ? '?id=' . (int) $existingProduct['id'] . '&amp;reset=1' : '?reset=1'; ?>">Azzera bozza</a>
            </div>
        </div>

        <?php if (!empty($flash)): ?>
            <div class="alert <?php echo htmlspecialchars($flash['type'] ?? 'info', ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($flash['message'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <div class="checkout-shell admin-product-shell">
            <div class="checkout-card checkout-form checkout-main admin-product-main">
                <?php if ($currentStep === 'dettagli'): ?>
                    <form method="POST" action="controllo-catalogo-prodotto<?php echo $existingProduct !== null ? '?id=' . (int) $existingProduct['id'] : ''; ?>" data-valida novalidate aria-labelledby="titolo-step-dettagli">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="action" value="save_details">

                        <div class="account-panel-head">
                            <span class="account-panel-kicker">Passo 1 di 3</span>
                            <h2 id="titolo-step-dettagli">Inserisci i dati del prodotto</h2>
                            <p class="checkout-muted">Titolo, categoria, prezzo, descrizione e disponibilita generale del prodotto condiviso fra tutte le filiali.</p>
                        </div>

                        <div class="campo-gruppo">
                            <label for="product-category-id">Categoria</label>
                            <select id="product-category-id" name="category_id" required aria-required="true">
                                <option value="">Seleziona una categoria</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo (int) $category['id']; ?>" <?php echo (int) ($draft['category_id'] ?? 0) === (int) $category['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars((string) $category['name'], ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="admin-inline-grid">
                            <div class="campo-gruppo">
                                <label for="product-name">Titolo</label>
                                <input type="text" id="product-name" name="name" required aria-required="true" minlength="3" maxlength="120" value="<?php echo htmlspecialchars((string) ($draft['name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="campo-gruppo">
                                <label for="product-price-cents">Prezzo (centesimi)</label>
                                <input type="number" id="product-price-cents" name="price_cents" required aria-required="true" min="1" step="1" value="<?php echo htmlspecialchars((string) ($draft['price_cents'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>

                        <div class="campo-gruppo">
                            <label for="product-description">Descrizione</label>
                            <textarea id="product-description" name="description" rows="5" required aria-required="true"><?php echo htmlspecialchars((string) ($draft['description'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>

                        <div class="campo-gruppo">
                            <label for="product-allergens">Allergeni</label>
                            <input type="text" id="product-allergens" name="allergens" maxlength="255" value="<?php echo htmlspecialchars((string) ($draft['allergens'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <fieldset class="checkout-fieldset">
                            <legend>Disponibilita generale</legend>
                            <label>
                                <input type="radio" name="is_available" value="1" <?php echo (string) ($draft['is_available'] ?? '1') === '1' ? 'checked' : ''; ?>>
                                Disponibile nel catalogo globale
                            </label>
                            <p class="checkout-option-note">Le filiali potranno comunque nasconderlo o segnarlo come non disponibile nel proprio catalogo locale.</p>
                            <label>
                                <input type="radio" name="is_available" value="0" <?php echo (string) ($draft['is_available'] ?? '1') === '0' ? 'checked' : ''; ?>>
                                Non disponibile a livello generale
                            </label>
                        </fieldset>

                        <div class="checkout-navigation">
                            <a class="bottone-secondario" href="controllo-catalogo">&larr; Annulla</a>
                            <button class="bottone-primario" type="submit">Vai all'immagine &rarr;</button>
                        </div>
                    </form>
                <?php elseif ($currentStep === 'immagine'): ?>
                    <?php $imageStepQuery = http_build_query(array_filter(['id' => $existingProduct['id'] ?? null, 'step' => 'immagine'])); ?>
                    <form method="POST" enctype="multipart/form-data" action="controllo-catalogo-prodotto<?php echo $imageStepQuery !== '' ? '?' . $imageStepQuery : ''; ?>" aria-labelledby="titolo-step-immagine" id="catalog-image-step-form">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="action" value="save_image">

                        <div class="account-panel-head">
                            <span class="account-panel-kicker">Passo 2 di 3</span>
                            <h2 id="titolo-step-immagine">Scegli l'immagine e regola l'inquadratura</h2>
                            <p class="checkout-muted">Il ritaglio resta leggero e non distruttivo: salviamo il punto focale per far “cadere bene” l immagine nelle card del catalogo.</p>
                        </div>

                        <div class="admin-product-media-grid">
                            <div class="admin-product-preview-card">
                                <p class="account-panel-kicker">Anteprima card</p>
                                <div class="admin-product-image-frame">
                                    <img
                                        src="<?php echo !empty($draft['image_path']) ? htmlspecialchars((string) $draft['image_path'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                                        alt="Anteprima del prodotto nel catalogo globale Smash Burger con inquadratura regolabile"
                                        data-image-focus-preview="true"
                                        <?php echo empty($draft['image_path']) ? 'hidden' : ''; ?>
                                        style="object-position: <?php echo (int) ($draft['image_focus_x'] ?? 50); ?>% <?php echo (int) ($draft['image_focus_y'] ?? 50); ?>%;">
                                    <div class="admin-product-image-placeholder" data-image-focus-empty="true" <?php echo !empty($draft['image_path']) ? 'hidden' : ''; ?>>
                                        Nessuna immagine caricata
                                    </div>
                                </div>
                            </div>

                            <div class="admin-product-media-controls">
                                <div class="campo-gruppo">
                                    <label for="product-image">Immagine prodotto</label>
                                    <input type="file" id="product-image" name="product_image" accept=".jpg,.jpeg,.png,.webp" data-image-upload-input="true">
                                    <p class="checkout-muted">Formati supportati: JPG, PNG, WEBP. Peso massimo 1 MB.</p>
                                </div>

                                <div class="campo-gruppo">
                                    <label for="product-image-focus-x">Fuoco orizzontale</label>
                                    <input type="range" id="product-image-focus-x" name="image_focus_x" min="0" max="100" step="1" value="<?php echo (int) ($draft['image_focus_x'] ?? 50); ?>" data-image-focus-x="true">
                                    <p class="checkout-muted">Valore attuale: <strong data-image-focus-x-output="true"><?php echo (int) ($draft['image_focus_x'] ?? 50); ?></strong>%</p>
                                </div>

                                <div class="campo-gruppo">
                                    <label for="product-image-focus-y">Fuoco verticale</label>
                                    <input type="range" id="product-image-focus-y" name="image_focus_y" min="0" max="100" step="1" value="<?php echo (int) ($draft['image_focus_y'] ?? 50); ?>" data-image-focus-y="true">
                                    <p class="checkout-muted">Valore attuale: <strong data-image-focus-y-output="true"><?php echo (int) ($draft['image_focus_y'] ?? 50); ?></strong>%</p>
                                </div>
                            </div>
                        </div>

                    </form>

                    <div class="checkout-navigation admin-product-step-actions">
                        <form method="POST" action="controllo-catalogo-prodotto<?php echo $imageStepQuery !== '' ? '?' . $imageStepQuery : ''; ?>" class="admin-product-back-form">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="hidden" name="action" value="go_to_details">
                            <button class="bottone-secondario" type="submit">&larr; Torna ai dati</button>
                        </form>
                        <button class="bottone-primario" type="submit" form="catalog-image-step-form">Vai al riepilogo &rarr;</button>
                    </div>
                <?php else: ?>
                    <div class="account-panel-head">
                        <span class="account-panel-kicker">Passo 3 di 3</span>
                        <h2 id="titolo-step-riepilogo">Riepilogo finale</h2>
                        <p class="checkout-muted">Controlla i dati del prodotto globale prima della conferma definitiva.</p>
                    </div>

                    <div class="admin-product-review-grid">
                        <article class="checkout-card admin-product-review-card">
                            <h3>Scheda prodotto</h3>
                            <ul class="riepilogo-lista">
                                <li><span>Categoria</span><strong><?php echo htmlspecialchars($categoryNameById[(int) ($draft['category_id'] ?? 0)] ?? 'Non definita', ENT_QUOTES, 'UTF-8'); ?></strong></li>
                                <li><span>Titolo</span><strong><?php echo htmlspecialchars((string) ($draft['name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></strong></li>
                                <li><span>Prezzo</span><strong><?php echo money_eur((int) ($draft['price_cents'] ?? 0)); ?></strong></li>
                                <li><span>Disponibilita generale</span><strong><?php echo (string) ($draft['is_available'] ?? '1') === '1' ? 'Disponibile' : 'Non disponibile'; ?></strong></li>
                                <li><span>Allergeni</span><strong><?php echo htmlspecialchars((string) (($draft['allergens'] ?? '') !== '' ? $draft['allergens'] : 'Non indicati'), ENT_QUOTES, 'UTF-8'); ?></strong></li>
                            </ul>
                            <p class="checkout-muted"><?php echo nl2br(htmlspecialchars((string) ($draft['description'] ?? ''), ENT_QUOTES, 'UTF-8')); ?></p>
                        </article>

                        <article class="checkout-card admin-product-review-card">
                            <h3>Anteprima visiva</h3>
                            <div class="admin-product-image-frame admin-product-image-frame--large">
                                <?php if (!empty($draft['image_path'])): ?>
                                    <img
                                        src="<?php echo htmlspecialchars((string) $draft['image_path'], ENT_QUOTES, 'UTF-8'); ?>"
                                        alt="Anteprima finale del prodotto nel catalogo globale Smash Burger"
                                        style="object-position: <?php echo (int) ($draft['image_focus_x'] ?? 50); ?>% <?php echo (int) ($draft['image_focus_y'] ?? 50); ?>%;">
                                <?php else: ?>
                                    <div class="admin-product-image-placeholder">
                                        Nessuna immagine caricata
                                    </div>
                                <?php endif; ?>
                            </div>
                            <ul class="riepilogo-lista">
                                <li><span>Fuoco orizzontale</span><strong><?php echo (int) ($draft['image_focus_x'] ?? 50); ?>%</strong></li>
                                <li><span>Fuoco verticale</span><strong><?php echo (int) ($draft['image_focus_y'] ?? 50); ?>%</strong></li>
                            </ul>
                        </article>
                    </div>

                    <div class="checkout-navigation">
                        <?php $reviewStepQuery = http_build_query(array_filter(['id' => $existingProduct['id'] ?? null, 'step' => 'riepilogo'])); ?>
                        <form method="POST" action="controllo-catalogo-prodotto<?php echo $reviewStepQuery !== '' ? '?' . $reviewStepQuery : ''; ?>">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="hidden" name="action" value="go_to_image">
                            <button class="bottone-secondario" type="submit">&larr; Torna all immagine</button>
                        </form>

                        <form method="POST" action="controllo-catalogo-prodotto<?php echo $reviewStepQuery !== '' ? '?' . $reviewStepQuery : ''; ?>">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="hidden" name="action" value="confirm_product">
                            <button class="bottone-primario" type="submit"><?php echo $existingProduct !== null ? 'Conferma modifica' : 'Conferma prodotto'; ?></button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>

            <aside class="checkout-card account-side admin-product-side" aria-labelledby="titolo-side-catalogo-prodotto">
                <h2 id="titolo-side-catalogo-prodotto"><?php echo $isReviewStep ? 'Prima di confermare' : 'Guida rapida'; ?></h2>
                <ul class="riepilogo-lista">
                    <li><span>Catalogo</span><strong>Condiviso fra tutte le sedi</strong></li>
                    <li><span>Visibilita branch</span><strong>Decisa localmente dai manager</strong></li>
                    <li><span>Immagine</span><strong>Peso massimo 1 MB</strong></li>
                </ul>
                <p class="checkout-muted account-note">
                    <?php if ($isReviewStep): ?>
                        Dopo la conferma il prodotto entra nel catalogo globale. Ogni filiale potra poi mostrarlo o lasciarlo nascosto nel proprio listino locale.
                    <?php else: ?>
                        Il flusso salva una bozza di sessione, cosi puoi muoverti tra i passaggi senza perdere i dati gia inseriti.
                    <?php endif; ?>
                </p>
            </aside>
        </div>
    </div>
</section>
