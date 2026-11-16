// Campo de caracteres do topo: a marca KDEVS é desenhada com trechos de código e
// "compila" da esquerda para a direita. Perto do ponteiro os caracteres embaralham.
// Decorativo (canvas com aria-hidden); com movimento reduzido fica um quadro estático.

const SOURCE = 'const build=(idea)=>ship(test(code(idea)));';
const NOISE = '{}[]()<>=/;:+*#$_01';
const PAPER = '242, 240, 233';
const LIME = '201, 242, 63';

export interface GlyphField {
  setProgress(value: number): void;
  destroy(): void;
}

export function createGlyphField(canvas: HTMLCanvasElement, interactive: boolean): GlyphField | null {
  const ctx = canvas.getContext('2d');
  if (!ctx) return null;

  let cols = 0, rows = 0, cellW = 12, cellH = 18, width = 0, height = 0;
  let mask = new Uint8Array(0);
  let order = new Float32Array(0);
  let grain = new Float32Array(0);
  let heat = new Float32Array(0);
  let progress = 0;
  let frame = 0;
  let last = 0;
  let onScreen = true;

  function layout() {
    const rect = canvas.getBoundingClientRect();
    width = rect.width;
    height = rect.height;
    if (width < 2 || height < 2) return;
    const dpr = Math.min(window.devicePixelRatio || 1, 2);
    canvas.width = Math.round(width * dpr);
    canvas.height = Math.round(height * dpr);
    ctx!.setTransform(dpr, 0, 0, dpr, 0, 0);

    cellW = width < 640 ? 5 : width < 1100 ? 8 : 9;
    cellH = Math.round(cellW * 1.5);
    cols = Math.ceil(width / cellW);
    rows = Math.ceil(height / cellH);

    const off = document.createElement('canvas');
    off.width = Math.ceil(width);
    off.height = Math.ceil(height);
    const pen = off.getContext('2d', { willReadFrequently: true });
    if (!pen) return;
    const family = '"Bricolage Grotesque Variable", system-ui, sans-serif';
    let size = height * 1.1;
    pen.font = `700 ${size}px ${family}`;
    const limit = width * (width < 640 ? 0.99 : 0.94);
    const measured = pen.measureText('KDEVS').width;
    if (measured > limit) size *= limit / measured;
    pen.font = `700 ${size}px ${family}`;
    const metrics = pen.measureText('KDEVS');
    const x = (width - metrics.width) / 2;
    const y = height / 2 + (metrics.actualBoundingBoxAscent - metrics.actualBoundingBoxDescent) / 2;
    pen.fillStyle = '#fff';
    pen.fillText('KDEVS', x, y);
    const pixels = pen.getImageData(0, 0, off.width, off.height).data;

    const total = cols * rows;
    mask = new Uint8Array(total);
    order = new Float32Array(total);
    grain = new Float32Array(total);
    heat = new Float32Array(total);
    for (let row = 0; row < rows; row++) {
      for (let col = 0; col < cols; col++) {
        const i = row * cols + col;
        const px = Math.min(off.width - 1, Math.floor(col * cellW + cellW / 2));
        const py = Math.min(off.height - 1, Math.floor(row * cellH + cellH / 2));
        mask[i] = pixels[(py * off.width + px) * 4 + 3] > 110 ? 1 : 0;
        grain[i] = Math.random();
        order[i] = 0.72 * (col / cols) + 0.28 * grain[i];
      }
    }
    request();
  }

  function draw(now: number) {
    frame = 0;
    const dt = last ? Math.min(64, now - last) : 16;
    last = now;
    const decay = Math.pow(0.0025, dt / 1000);
    const tick = Math.floor(now / 80);
    let busy = false;

    ctx!.clearRect(0, 0, width, height);
    ctx!.font = `700 ${Math.round(cellH * 0.82)}px "JetBrains Mono Variable", ui-monospace, monospace`;
    ctx!.textAlign = 'center';
    ctx!.textBaseline = 'middle';

    for (let i = 0; i < mask.length; i++) {
      const hot = heat[i];
      if (hot > 0.02) { heat[i] = hot * decay; busy = true; } else if (hot) heat[i] = 0;
      const inside = mask[i] === 1;
      const settled = progress >= order[i];
      let glyph = '';
      let color = '';
      if (hot > 0.02) {
        glyph = NOISE[(i * 7 + tick) % NOISE.length];
        color = `rgba(${LIME}, ${inside ? 0.45 + 0.55 * hot : 0.75 * hot})`;
      } else if (inside) {
        if (settled) { glyph = SOURCE[i % SOURCE.length]; color = `rgba(${PAPER}, 0.94)`; }
        else if (progress >= order[i] - 0.22) { glyph = NOISE[(i * 3 + tick) % NOISE.length]; color = `rgba(${PAPER}, 0.38)`; }
      } else if (settled && grain[i] < 0.05) {
        glyph = SOURCE[(i * 5) % SOURCE.length];
        color = `rgba(${PAPER}, 0.14)`;
      }
      if (!glyph) continue;
      ctx!.fillStyle = color;
      ctx!.fillText(glyph, (i % cols) * cellW + cellW / 2, Math.floor(i / cols) * cellH + cellH / 2);
    }
    if (busy || (progress > 0 && progress < 1)) request();
    else last = 0;
  }

  function request() {
    if (!frame && onScreen) frame = requestAnimationFrame(draw);
  }

  function onPointer(event: PointerEvent) {
    const rect = canvas.getBoundingClientRect();
    const col = (event.clientX - rect.left) / cellW;
    const row = (event.clientY - rect.top) / cellH;
    const reach = 7;
    for (let r = Math.max(0, Math.floor(row - reach)); r <= Math.min(rows - 1, Math.ceil(row + reach)); r++) {
      for (let c = Math.max(0, Math.floor(col - reach * 1.5)); c <= Math.min(cols - 1, Math.ceil(col + reach * 1.5)); c++) {
        const distance = Math.hypot((c - col) / 1.5, r - row);
        if (distance < reach) { const i = r * cols + c; heat[i] = Math.max(heat[i], 1 - distance / reach); }
      }
    }
    request();
  }

  const resize = new ResizeObserver(layout);
  resize.observe(canvas);
  const watch = new IntersectionObserver(([entry]) => { onScreen = entry.isIntersecting; if (onScreen) request(); });
  watch.observe(canvas);
  if (interactive) canvas.addEventListener('pointermove', onPointer, { passive: true });
  document.fonts?.ready.then(layout);

  return {
    setProgress(value) { progress = value; request(); },
    destroy() {
      resize.disconnect();
      watch.disconnect();
      canvas.removeEventListener('pointermove', onPointer);
      if (frame) cancelAnimationFrame(frame);
    },
  };
}
