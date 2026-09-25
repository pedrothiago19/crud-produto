document.addEventListener('DOMContentLoaded', () => {
    const formCesta = document.getElementById('formCesta');

    if (formCesta) {
        formCesta.addEventListener('submit', async (event) => {
            event.preventDefault();

            const checks = [
                ...document.querySelectorAll('.produto-check:checked')
            ];

            if (!checks.length) {
                alert('Selecione pelo menos um produto.');
                return;
            }

            const formData = new FormData(formCesta);
            const button = document.getElementById('btnCesta');

            button.disabled = true;
            button.textContent = 'Adicionando...';

            try {
                const response = await fetch(
                    '../ajax/cesta.php',
                    {
                        method: 'POST',
                        body: formData
                    }
                );

                const data = await response.json();

                if (!data.success) {
                    throw new Error(
                        data.message || 'Erro ao adicionar.'
                    );
                }

                window.location.href = data.data.redirect;

            } catch (error) {
                alert(error.message);

                button.disabled = false;
                button.textContent = 'Adicionar à Cesta';
            }
        });
    }
});