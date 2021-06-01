<?php
if (!function_exists("magicform_getLoadingIcon")) {
    function magicform_getLoadingIcon($icon, $color)
    {
        $color = magicform_convertRgba($color);
        switch ($icon) {
            case "Loading 1": ?>
                <svg width="40" height="40" viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg">
                    <path fill="<?php echo esc_attr($color); ?>" d="M43.935 25.145c0-10.318-8.364-18.683-18.683-18.683-10.318 0-18.683 8.365-18.683 18.683h4.068c0-8.071 6.543-14.615 14.615-14.615s14.615 6.543 14.615 14.615h4.068z">
                        <animateTransform attributeName="transform" attributeType="xml" dur="0.6s" from="0 25 25" repeatCount="indefinite" to="360 25 25" type="rotate" />
                    </path>
                </svg>
            <?php break;
            case "Loading 2": ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40">
                    <path fill="<?php echo esc_attr($color); ?>" opacity=".2" d="M20.201 5.169c-8.254 0-14.946 6.692-14.946 14.946 0 8.255 6.692 14.946 14.946 14.946s14.946-6.691 14.946-14.946c-.001-8.254-6.692-14.946-14.946-14.946zm0 26.58c-6.425 0-11.634-5.208-11.634-11.634 0-6.425 5.209-11.634 11.634-11.634 6.425 0 11.633 5.209 11.633 11.634 0 6.426-5.208 11.634-11.633 11.634z" />
                    <path fill="<?php echo esc_attr($color); ?>" d="M26.013 10.047l1.654-2.866a14.855 14.855 0 00-7.466-2.012v3.312c2.119 0 4.1.576 5.812 1.566z">
                        <animateTransform attributeType="xml" attributeName="transform" type="rotate" from="0 20 20" to="360 20 20" dur="0.5s" repeatCount="indefinite" />
                    </path>
                </svg>
            <?php break;
            case "Loading 3": ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 50 50">
                    <path fill="<?php echo esc_attr($color); ?>" d="M25.251 6.461c-10.318 0-18.683 8.365-18.683 18.683h4.068c0-8.071 6.543-14.615 14.615-14.615V6.461z">
                        <animateTransform attributeType="xml" attributeName="transform" type="rotate" from="0 25 25" to="360 25 25" dur="0.6s" repeatCount="indefinite" />
                    </path>
                </svg>
            <?php break;
            case "Loading 4": ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40">
                    <path fill="<?php echo esc_attr($color); ?>" d="M0 0h4v7H0z">
                        <animateTransform attributeType="xml" attributeName="transform" type="scale" values="1,1; 1,3; 1,1" begin="0s" dur="0.6s" repeatCount="indefinite" />
                    </path>
                    <path fill="<?php echo esc_attr($color); ?>" d="M10 0h4v7h-4z">
                        <animateTransform attributeType="xml" attributeName="transform" type="scale" values="1,1; 1,3; 1,1" begin="0.2s" dur="0.6s" repeatCount="indefinite" />
                    </path>
                    <path fill="<?php echo esc_attr($color); ?>" d="M20 0h4v7h-4z">
                        <animateTransform attributeType="xml" attributeName="transform" type="scale" values="1,1; 1,3; 1,1" begin="0.4s" dur="0.6s" repeatCount="indefinite" />
                    </path>
                </svg>
            <?php break;
            case "Loading 5": ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="40" viewBox="0 0 24 30">
                    <path fill="<?php echo esc_attr($color); ?>" d="M0 0h4v10H0z">
                        <animateTransform attributeType="xml" attributeName="transform" type="translate" values="0 0; 0 20; 0 0" begin="0" dur="0.6s" repeatCount="indefinite" />
                    </path>
                    <path fill="<?php echo esc_attr($color); ?>" d="M10 0h4v10h-4z">
                        <animateTransform attributeType="xml" attributeName="transform" type="translate" values="0 0; 0 20; 0 0" begin="0.2s" dur="0.6s" repeatCount="indefinite" />
                    </path>
                    <path fill="<?php echo esc_attr($color); ?>" d="M20 0h4v10h-4z">
                        <animateTransform attributeType="xml" attributeName="transform" type="translate" values="0 0; 0 20; 0 0" begin="0.4s" dur="0.6s" repeatCount="indefinite" />
                    </path>
                </svg>
            <?php break;
            case "Loading 6": ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="40" viewBox="0 0 24 30">
                    <path fill="<?php echo esc_attr($color); ?>" d="M0 0h4v20H0z">
                        <animate attributeName="opacity" attributeType="XML" values="1; .2; 1" begin="0s" dur="0.6s" repeatCount="indefinite" />
                    </path>
                    <path fill="<?php echo esc_attr($color); ?>" d="M7 0h4v20H7z">
                        <animate attributeName="opacity" attributeType="XML" values="1; .2; 1" begin="0.2s" dur="0.6s" repeatCount="indefinite" />
                    </path>
                    <path fill="<?php echo esc_attr($color); ?>" d="M14 0h4v20h-4z">
                        <animate attributeName="opacity" attributeType="XML" values="1; .2; 1" begin="0.4s" dur="0.6s" repeatCount="indefinite" />
                    </path>
                </svg>
            <?php break;
            case "Loading 7": ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="40" viewBox="0 0 24 30">
                    <path fill="<?php echo esc_attr($color); ?>" opacity=".2" d="M0 10h4v10H0z">
                        <animate attributeName="opacity" attributeType="XML" values="0.2; 1; .2" begin="0s" dur="0.6s" repeatCount="indefinite" />
                        <animate attributeName="height" attributeType="XML" values="10; 20; 10" begin="0s" dur="0.6s" repeatCount="indefinite" />
                        <animate attributeName="y" attributeType="XML" values="10; 5; 10" begin="0s" dur="0.6s" repeatCount="indefinite" />
                    </path>
                    <path fill="<?php echo esc_attr($color); ?>" opacity=".2" d="M8 10h4v10H8z">
                        <animate attributeName="opacity" attributeType="XML" values="0.2; 1; .2" begin="0.15s" dur="0.6s" repeatCount="indefinite" />
                        <animate attributeName="height" attributeType="XML" values="10; 20; 10" begin="0.15s" dur="0.6s" repeatCount="indefinite" />
                        <animate attributeName="y" attributeType="XML" values="10; 5; 10" begin="0.15s" dur="0.6s" repeatCount="indefinite" />
                    </path>
                    <path fill="<?php echo esc_attr($color); ?>" opacity=".2" d="M16 10h4v10h-4z">
                        <animate attributeName="opacity" attributeType="XML" values="0.2; 1; .2" begin="0.3s" dur="0.6s" repeatCount="indefinite" />
                        <animate attributeName="height" attributeType="XML" values="10; 20; 10" begin="0.3s" dur="0.6s" repeatCount="indefinite" />
                        <animate attributeName="y" attributeType="XML" values="10; 5; 10" begin="0.3s" dur="0.6s" repeatCount="indefinite" />
                    </path>
                </svg>
            <?php break;
            case "Loading 8": ?>
                <svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="40" height="40" viewBox="0 0 128 128">
                    <path fill="<?php echo esc_attr($color); ?>" d="M64.4 16a49 49 0 00-50 48 51 51 0 0050 52.2 53 53 0 0054-52c-.7-48-45-55.7-45-55.7s45.3 3.8 49 55.6c.8 32-24.8 59.5-58 60.2-33 .8-61.4-25.7-62-60C1.3 29.8 28.8.6 64.3 0c0 0 8.5 0 8.7 8.4 0 8-8.6 7.6-8.6 7.6z">
                        <animateTransform attributeName="transform" type="rotate" from="0 64 64" to="360 64 64" dur="1800ms" repeatCount="indefinite" />
                    </path>
                </svg>
<?php break;
        }
    }
}
