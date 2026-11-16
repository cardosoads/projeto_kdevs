import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { createGlyphField } from './glyph-field';
import { initBrief } from './brief';

gsap.registerPlugin(ScrollTrigger);
const EASE = 'expo.out';

initBrief();

const media = gsap.matchMedia();
media.add(
  { motion: '(prefers-reduced-motion: no-preference)', still: '(prefers-reduced-motion: reduce)', wide: '(min-width: 960px)', pointer: '(hover: hover) and (pointer: fine)' },
  (context) => {
    const { motion, wide, pointer } = context.conditions as Record<string, boolean>;

    // Topo: a marca compila uma vez; o texto entra em sequência curta.
    const canvas = document.querySelector<HTMLCanvasElement>('[data-glyph-field]');
    const field = canvas ? createGlyphField(canvas, motion && pointer) : null;
    if (field) {
      if (motion) {
        const state = { value: 0 };
        gsap.to(state, { value: 1, duration: 1.9, delay: 0.15, ease: 'power2.out', onUpdate: () => field.setProgress(state.value) });
      } else field.setProgress(1);
    }
    if (motion) {
      gsap.from('[data-hero-item]', { y: 20, autoAlpha: 0, duration: 0.8, ease: EASE, stagger: 0.07, clearProps: 'all' });
    }

    // Serviços: o marcador acompanha a linha em uso (ponteiro ou teclado).
    const services = document.querySelector<HTMLElement>('[data-services]');
    const marker = services?.querySelector<HTMLElement>('[data-services-marker]');
    const rows = services ? Array.from(services.querySelectorAll<HTMLElement>('[data-services-row]')) : [];
    const cleanups: Array<() => void> = [];
    if (services && marker && rows.length) {
      let current = rows[0];
      const place = (row: HTMLElement, instant = false) => {
        current = row;
        const title = row.querySelector<HTMLElement>('h3') ?? row;
        const top = title.getBoundingClientRect().top - services.getBoundingClientRect().top + title.offsetHeight / 2 - marker.offsetHeight / 2;
        gsap.to(marker, { y: top, duration: instant || !motion ? 0 : 0.35, ease: EASE, overwrite: true });
      };
      place(current, true);
      const onResize = () => place(current, true);
      window.addEventListener('resize', onResize);
      document.fonts?.ready.then(onResize);
      cleanups.push(() => window.removeEventListener('resize', onResize));
      for (const row of rows) {
        const enter = () => place(row);
        row.addEventListener('pointerenter', enter);
        cleanups.push(() => row.removeEventListener('pointerenter', enter));
      }
    }

    // Fundos de seção. Com movimento reduzido nada disto roda e vale o desenho estático do CSS.
    if (motion) {
      const between = (min: number, max: number) => min + Math.random() * (max - min);
      const place = (el: HTMLElement, x: number, y: number) => {
        const left = x - el.offsetWidth / 2;
        const top = y - el.offsetHeight / 2;
        gsap.set(el, { x: left, y: top });
        // Mantém o desenho aceso alinhado à malha fixa enquanto desliza.
        const cell = Number(el.dataset.cell);
        if (cell) el.style.backgroundPosition = `${-(((left % cell) + cell) % cell)}px ${-(((top % cell) + cell) % cell)}px, 0 0`;
      };

      document.querySelectorAll<HTMLElement>('[data-ambient]').forEach((layer) => {
        const stage = layer.parentElement;
        if (!stage) return;
        const loops: gsap.core.Animation[] = [];
        let live = false;

        // Luz de fundo desloca com a rolagem.
        const tint = layer.querySelector<HTMLElement>('[data-drift]');
        if (tint) gsap.fromTo(tint, { y: -90 }, { y: 90, ease: 'none', scrollTrigger: { trigger: stage, start: 'top bottom', end: 'bottom top', scrub: 0.8 } });

        // Foco que passeia sozinho pela parte visível da seção (vale também no toque).
        layer.querySelectorAll<HTMLElement>('[data-wander]').forEach((el) => {
          const at = { x: layer.offsetWidth * 0.75, y: 300 };
          let run: gsap.core.Tween | null = null;
          const next = () => {
            const top = Math.min(Math.max(-layer.getBoundingClientRect().top, 0), Math.max(layer.offsetHeight - window.innerHeight, 0));
            run = gsap.to(at, {
              x: between(0.08, 0.92) * layer.offsetWidth,
              y: top + between(0.1, 0.9) * Math.min(window.innerHeight, layer.offsetHeight),
              duration: between(6, 10), ease: 'sine.inOut', paused: !live,
              onUpdate: () => place(el, at.x, at.y), onComplete: next,
            });
          };
          place(el, at.x, at.y);
          gsap.set(el, { opacity: 0.6 });
          next();
          loops.push({ paused: (value: boolean) => run?.paused(value) } as unknown as gsap.core.Animation);
          cleanups.push(() => { run?.kill(); gsap.set(el, { clearProps: 'all' }); });
        });

        // Anéis respiram.
        const pulse = layer.querySelector<HTMLElement>('[data-pulse]');
        if (pulse) loops.push(gsap.fromTo(pulse, { scale: 0.92 }, { scale: 1.08, duration: 5, ease: 'sine.inOut', repeat: -1, yoyo: true, paused: true }));

        // Linhas de fluxo correm sem parar e aceleram com a rolagem.
        const tracks = Array.from(layer.querySelectorAll<HTMLElement>('[data-track]'));
        if (tracks.length) {
          const flow = gsap.timeline({ paused: true });
          tracks.forEach((track, index) => {
            const period = Number(track.dataset.period) || 240;
            const from = index % 2 ? period : 0;
            flow.fromTo(track, { x: from }, { x: period - from, duration: between(9, 15), ease: 'none', repeat: -1 }, 0);
          });
          loops.push(flow);
          ScrollTrigger.create({
            trigger: stage, start: 'top bottom', end: 'bottom top',
            onUpdate: (self) => {
              const boost = 1 + Math.min(Math.abs(self.getVelocity()) / 350, 7);
              gsap.to(flow, { timeScale: boost, duration: 0.2, overwrite: true, onComplete: () => { gsap.to(flow, { timeScale: 1, duration: 1.2 }); } });
            },
          });
        }

        // Linhas de código deslizam em sentidos alternados conforme a rolagem.
        layer.querySelectorAll<HTMLElement>('[data-row]').forEach((row, index) => {
          const way = index % 2 ? 1 : -1;
          gsap.fromTo(row, { x: way * -220 }, { x: way * 220, ease: 'none', scrollTrigger: { trigger: stage, start: 'top bottom', end: 'bottom top', scrub: 0.7 } });
        });

        // Foco que segue o ponteiro (só com mouse).
        if (pointer) {
          layer.querySelectorAll<HTMLElement>('[data-spot]').forEach((el) => {
            const rests = el.hasAttribute('data-rest');
            const at = { x: 0, y: 0 };
            const apply = () => place(el, at.x, at.y);
            const toX = gsap.quickTo(at, 'x', { duration: rests ? 0.9 : 0.5, ease: 'power3.out', onUpdate: apply });
            const toY = gsap.quickTo(at, 'y', { duration: rests ? 0.9 : 0.5, ease: 'power3.out', onUpdate: apply });
            let inside = false;
            const follow = (event: PointerEvent) => {
              const box = stage.getBoundingClientRect();
              const x = event.clientX - box.left;
              const y = event.clientY - box.top;
              if (!inside) {
                inside = true;
                if (!rests) { at.x = x; at.y = y; apply(); gsap.to(el, { opacity: 1, duration: 0.4, ease: 'power2.out', overwrite: 'auto' }); }
                else if (!at.x && !at.y) { const now = el.getBoundingClientRect(); at.x = now.left - box.left + now.width / 2; at.y = now.top - box.top + now.height / 2; }
              }
              toX(x); toY(y);
            };
            const exit = () => { inside = false; if (!rests) gsap.to(el, { opacity: 0, duration: 0.5, ease: 'power2.out', overwrite: 'auto' }); };
            stage.addEventListener('pointermove', follow, { passive: true });
            stage.addEventListener('pointerleave', exit);
            cleanups.push(() => {
              stage.removeEventListener('pointermove', follow);
              stage.removeEventListener('pointerleave', exit);
              gsap.killTweensOf([el, at]);
              gsap.set(el, { clearProps: 'all' });
            });
          });
        }

        // Laços contínuos só rodam com a seção na tela.
        ScrollTrigger.create({
          trigger: stage, start: 'top bottom', end: 'bottom top',
          onToggle: (self) => { live = self.isActive; loops.forEach((loop) => loop.paused(!live)); },
        });
        cleanups.push(() => loops.forEach((loop) => loop.kill?.()));
      });
    }

    // Tecnologias: destaque por camada funciona sempre; a rotação das órbitas só com movimento.
    const techs = document.querySelector<HTMLElement>('[data-techs]');
    if (techs) {
      const rings = Array.from(techs.querySelectorAll<HTMLElement>('[data-ring]'));
      const legend = Array.from(techs.querySelectorAll<HTMLElement>('[data-legend]'));
      const mark = (index: number | null) => {
        rings.forEach((ring, i) => ring.classList.toggle('is-on', i === index));
        legend.forEach((item, i) => item.classList.toggle('is-on', i === index));
      };
      const off = () => mark(null);
      // Os anéis se sobrepõem, então quem recebe o ponteiro é cada bloco, não o anel.
      const targets = [...legend, ...rings.flatMap((ring) => Array.from(ring.querySelectorAll<HTMLElement>('[data-tech]')))];
      targets.forEach((el) => {
        const index = Number(el.dataset.legend ?? el.closest<HTMLElement>('[data-ring]')?.dataset.ring);
        const on = () => mark(index);
        el.addEventListener('pointerenter', on);
        el.addEventListener('pointerleave', off);
        cleanups.push(() => { el.removeEventListener('pointerenter', on); el.removeEventListener('pointerleave', off); });
      });
      cleanups.push(off);

      const cosmos = techs.querySelector<HTMLElement>('[data-cosmos]');
      const sys = techs.querySelector<HTMLElement>('[data-cosmos-sys]');
      if (motion && cosmos && sys) {
        const spin = gsap.timeline({ paused: true });
        rings.forEach((ring, i) => {
          const tiles = ring.querySelectorAll<HTMLElement>('[data-tech]');
          const turn = { value: 0 };
          spin.to(turn, {
            value: i % 2 ? -360 : 360, duration: 150 + i * 40, ease: 'none', repeat: -1,
            // Os blocos giram ao contrário do anel para o logo ficar sempre em pé.
            onUpdate: () => { gsap.set(ring, { rotation: turn.value }); gsap.set(tiles, { rotation: -turn.value }); },
          }, 0);
          cleanups.push(() => gsap.set([ring, ...tiles], { clearProps: 'transform' }));
        });
        ScrollTrigger.create({ trigger: cosmos, start: 'top bottom', end: 'bottom top', onToggle: (self) => spin.paused(!self.isActive) });
        if (pointer) {
          const tiltX = gsap.quickTo(sys, 'rotationX', { duration: 0.7, ease: 'power3.out' });
          const tiltY = gsap.quickTo(sys, 'rotationY', { duration: 0.7, ease: 'power3.out' });
          const move = (event: PointerEvent) => {
            const box = cosmos.getBoundingClientRect();
            tiltY(((event.clientX - box.left) / box.width - 0.5) * 14);
            tiltX(((event.clientY - box.top) / box.height - 0.5) * -14);
          };
          // Com o ponteiro em cima, quase para: dá tempo de ler e apontar cada logo.
          const enter = () => gsap.to(spin, { timeScale: 0.12, duration: 0.5, overwrite: true });
          const leave = () => { tiltX(0); tiltY(0); gsap.to(spin, { timeScale: 1, duration: 0.8, overwrite: true }); };
          cosmos.addEventListener('pointermove', move, { passive: true });
          cosmos.addEventListener('pointerenter', enter);
          cosmos.addEventListener('pointerleave', leave);
          cleanups.push(() => { cosmos.removeEventListener('pointermove', move); cosmos.removeEventListener('pointerenter', enter); cosmos.removeEventListener('pointerleave', leave); });
        }
        cleanups.push(() => { spin.kill(); gsap.set(sys, { clearProps: 'all' }); });
      }
    }

    // Ilustrações: giram alguns graus com a rolagem e inclinam para o ponteiro.
    // Sem movimento (ou sem JS) ficam na inclinação fixa definida no CSS.
    if (motion) {
      const big = window.matchMedia('(min-width: 900px)').matches;
      document.querySelectorAll<HTMLElement>('[data-scene]').forEach((scene, index) => {
        const orbit = scene.querySelector<HTMLElement>('[data-orbit]');
        const screen = scene.querySelector<HTMLElement>('[data-screen]');
        if (!orbit || !screen) return;
        const side = index % 2 === 0 ? -1 : 1;
        const baseX = big ? 8 : 7;
        const baseY = side * (big ? 15 : 9);
        gsap.set(screen, { rotationX: baseX, rotationY: baseY });
        gsap.fromTo(orbit, { rotationY: side * -7, y: 28 }, {
          rotationY: side * 6, y: -28, ease: 'none',
          scrollTrigger: { trigger: scene, start: 'top bottom', end: 'bottom top', scrub: 0.6 },
        });
        if (pointer && big) {
          const tiltX = gsap.quickTo(screen, 'rotationX', { duration: 0.6, ease: 'power3.out' });
          const tiltY = gsap.quickTo(screen, 'rotationY', { duration: 0.6, ease: 'power3.out' });
          const move = (event: PointerEvent) => {
            const box = scene.getBoundingClientRect();
            tiltY(baseY + ((event.clientX - box.left) / box.width - 0.5) * 12);
            tiltX(baseX - ((event.clientY - box.top) / box.height - 0.5) * 10);
          };
          const leave = () => { tiltX(baseX); tiltY(baseY); };
          scene.addEventListener('pointermove', move, { passive: true });
          scene.addEventListener('pointerleave', leave);
          cleanups.push(() => { scene.removeEventListener('pointermove', move); scene.removeEventListener('pointerleave', leave); });
        }
        cleanups.push(() => gsap.set([orbit, screen], { clearProps: 'all' }));
      });
    }

    // Processo: o trilho avança com a rolagem e cada etapa acende quando ele chega.
    const process = document.querySelector<HTMLElement>('[data-process]');
    const fill = process?.querySelector<HTMLElement>('[data-process-fill]');
    const steps = process ? Array.from(process.querySelectorAll<HTMLElement>('[data-process-step]')) : [];
    if (motion && process && fill && steps.length) {
      const axis = wide ? 'scaleX' : 'scaleY';
      let marks: number[] = [];
      const measure = () => {
        const box = process.getBoundingClientRect();
        const length = wide ? box.width : box.height;
        marks = steps.map((step) => {
          const at = step.getBoundingClientRect();
          return ((wide ? at.left - box.left : at.top - box.top) + 6) / length;
        });
      };
      const light = (value: number) => steps.forEach((step, index) => step.toggleAttribute('data-pending', value + 0.004 < marks[index]));
      measure();
      light(0);
      gsap.fromTo(fill, { [axis]: 0 }, {
        [axis]: 1,
        ease: 'none',
        onUpdate() { light(this.progress()); },
        scrollTrigger: {
          trigger: process,
          start: 'top 78%',
          end: wide ? 'bottom 45%' : 'bottom 60%',
          scrub: 0.5,
          onRefresh: measure,
        },
      });
      document.fonts?.ready.then(() => ScrollTrigger.refresh());
      cleanups.push(() => steps.forEach((step) => step.removeAttribute('data-pending')));
    }

    return () => {
      field?.destroy();
      cleanups.forEach((run) => run());
      if (marker) gsap.set(marker, { clearProps: 'all' });
      if (fill) gsap.set(fill, { clearProps: 'all' });
    };
  },
);
