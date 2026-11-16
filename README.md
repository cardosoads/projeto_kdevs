# KDevs — site de kdevs.tech

Site estático de apresentação da KDevs. Astro 7, GSAP 3 (movimento) e fontes locais
(Bricolage Grotesque, Instrument Sans, JetBrains Mono via Fontsource). Sem backend,
formulário, analytics ou recurso de terceiros.

## Comandos

| Comando | O que faz |
| --- | --- |
| `npm install` | Instala as dependências |
| `npm run dev` | Servidor local de desenvolvimento |
| `npm run build` | Gera o site em `dist/` |
| `npm run check:csp` | Confere que o HTML gerado respeita a CSP de `vercel.json` |
| `npm run preview` | Serve `dist/` localmente |

## Onde editar

- `src/site.ts`: textos de produtos, situações, etapas, tecnologias e opções da mensagem.
- `src/styles/global.css`: cores, tipografia e layout.
- `src/scripts/`: marca em caracteres do topo, movimento (GSAP) e montagem da mensagem.

## Pendências antes de publicar

1. **WhatsApp.** Defina `PUBLIC_WHATSAPP_NUMBER` (só dígitos, com DDI e DDD) no ambiente
   de build da Vercel ou em `.env`. Sem ela, o site mostra apenas "Copiar mensagem" e um
   aviso de que o WhatsApp ainda não está ligado; nenhum link é gerado.
2. **Textos.** Produtos, situações e etapas do processo em `src/site.ts` são rascunho e precisam de
   confirmação da KDevs.
3. **Imagem de compartilhamento.** Não há `og:image`; falta uma arte da marca.

## Vercel

`vercel.json` define build, URLs limpas e cabeçalhos de segurança: CSP sem código inline
nem origens externas, HSTS, `nosniff`, bloqueio de iframe, `Referrer-Policy`,
`Permissions-Policy` e cache imutável para `/_astro/`. Nenhum deploy foi feito.

Ao adicionar scripts, estilos ou serviços externos, ajuste a CSP e rode `npm run check:csp`.

## Movimento e acessibilidade

- Tecnologias: órbitas por camada com os logos (`src/components/Stack.astro`, traçados em
  `src/logos.ts`, fontes e licenças em `LOGOS.md`). Giram devagar, quase param sob o ponteiro
  e destacam a camada apontada; cada logo tem o nome em texto para leitores de tela.
- Cada seção abaixo do topo tem um fundo próprio (`src/components/Ambient.astro`): malha de
  pontos, anéis, linhas de fluxo, linhas de código e malha de cruzes. Focos seguem o ponteiro
  ou passeiam sozinhos, as linhas de fluxo aceleram com a rolagem e os laços pausam fora da
  tela. São camadas decorativas (`aria-hidden`), animadas só por transform e opacidade.
- A marca do topo "compila" uma vez e reage ao ponteiro; as ilustrações de produto são
  telas em 3D (CSS, sem biblioteca extra) que giram alguns graus com a rolagem e inclinam
  para o ponteiro; o trilho do processo avança com a rolagem; um marcador acompanha a
  situação sob o ponteiro.
- As ilustrações ficam em `src/components/Mock.astro`: HTML e CSS originais, com rótulos de
  exemplo. Não são capturas de produtos reais.
- Com `prefers-reduced-motion: reduce` ou sem JavaScript, tudo aparece estático e completo;
  as telas mantêm só a inclinação fixa do CSS.
- O canvas é decorativo (`aria-hidden`); todo o conteúdo está no HTML e funciona sem JavaScript,
  exceto a prévia dinâmica da mensagem e o botão de copiar.
