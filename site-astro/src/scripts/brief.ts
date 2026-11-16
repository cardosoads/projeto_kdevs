import { composeMessage, whatsappUrl } from '../site';

// Monta a mensagem a partir das escolhas. Nada sai do navegador: o site só abre o
// WhatsApp (quando há número configurado) ou copia o texto.
export function initBrief() {
  const form = document.querySelector<HTMLFormElement>('[data-brief]');
  if (!form) return;
  const preview = form.querySelector<HTMLElement>('[data-brief-preview]');
  const open = form.querySelector<HTMLAnchorElement>('[data-brief-open]');
  const copy = form.querySelector<HTMLButtonElement>('[data-brief-copy]');
  const status = form.querySelector<HTMLElement>('[data-brief-status]');
  if (!preview) return;

  const read = () => {
    const data = new FormData(form);
    return composeMessage(String(data.get('need') ?? ''), String(data.get('stage') ?? ''), String(data.get('detail') ?? ''));
  };
  const update = () => {
    const message = read();
    preview.textContent = message;
    if (open) open.href = whatsappUrl(message);
    if (status) status.textContent = '';
  };
  form.addEventListener('input', update);
  form.addEventListener('submit', (event) => event.preventDefault());
  update();

  if (copy && navigator.clipboard) {
    copy.hidden = false;
    copy.addEventListener('click', async () => {
      try {
        await navigator.clipboard.writeText(read());
        if (status) status.textContent = 'Mensagem copiada';
      } catch {
        if (status) status.textContent = 'Não foi possível copiar. Selecione o texto da mensagem e copie manualmente';
      }
    });
  }
}
