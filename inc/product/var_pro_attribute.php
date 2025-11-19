<div class="vr_product_data">
    <?php
    $attribute = 'pa_avalynes-dydis';
    $sizes = explode(',', $product->get_attribute($attribute));
    $available_variations = $product->get_available_variations();
    $variation_map = [];

    foreach ($available_variations as $variation) {
        $val = $variation['attributes']["attribute_$attribute"] ?? '';
        $variation_map[$val] = $variation['variation_id'];
    }
    ?>
    <div class="d-flex flex-wrap gap-2">
        <?php foreach ($sizes as $size): $size = trim($size); ?>
            <button type="button" class="btn btn-outline-secondary size-button add_size" data-value="<?php echo esc_attr($size); ?>">
                <?php echo esc_html($size); ?>
            </button>
        <?php endforeach; ?>
    </div>

</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.add_size');
        const sizeInput = document.getElementById('selected_size_input');
        const variationInput = document.querySelector('input.variation_id');
        const variationMap = <?php echo json_encode($variation_map); ?>;

        buttons.forEach(button => {
            button.addEventListener('click', () => {
                const val = button.dataset.value;
                sizeInput.value = val;
                variationInput.value = variationMap[val] || '';

                buttons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
            });
        });
    });
</script>