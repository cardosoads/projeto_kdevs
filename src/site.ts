// Conteúdo e configuração do site em um só lugar.
// RASCUNHO a confirmar pela KDevs antes de publicar: os produtos e as situações seguem as áreas do perfil
// profissional informado (web, SaaS, software personalizado, UX, banco de dados, IA aplicada);
// as etapas do processo são uma proposta de texto, não um registro de como a KDevs opera.

const rawNumber = (import.meta.env.PUBLIC_WHATSAPP_NUMBER ?? '').replace(/\D/g, '');

export const site = {
  name: 'KDevs',
  domain: 'kdevs.tech',
  title: 'KDevs · Desenvolvimento de software sob medida',
  description:
    'A KDevs desenvolve software e soluções de tecnologia para empresas: aplicações web, produtos SaaS, software personalizado e IA aplicada.',
  // Só vale como número com 10 a 15 dígitos; fora disso a integração fica pendente.
  whatsappNumber: /^\d{10,15}$/.test(rawNumber) ? rawNumber : '',
};

export const products = [
  {
    kind: 'web', name: 'Aplicações web',
    lead: 'Sistemas que rodam no navegador e funcionam no computador e no celular, sem instalar nada.',
    points: ['Portais de clientes, áreas logadas e painéis administrativos', 'Sites institucionais leves e fáceis de atualizar', 'Laravel, Vue, React ou Next.js, conforme o que o projeto pede'],
    alt: 'Ilustração de um sistema web com menu lateral, lista de pedidos e botão de novo pedido',
  },
  {
    kind: 'saas', name: 'Produtos SaaS',
    lead: 'Para quem quer transformar um serviço ou um conhecimento em produto por assinatura.',
    points: ['Cadastro, login e contas de equipe', 'Planos e cobrança recorrente integrada a meios de pagamento', 'Área do cliente e painel de gestão para acompanhar o negócio'],
    alt: 'Ilustração de três planos de assinatura lado a lado, com um deles em destaque',
  },
  {
    kind: 'custom', name: 'Software personalizado',
    lead: 'Quando nenhum sistema pronto encaixa no jeito que a sua empresa trabalha.',
    points: ['Pedidos, estoque, atendimento ou financeiro no mesmo lugar', 'Regras e permissões desenhadas para a sua equipe', 'Relatórios com os dados que você realmente consulta'],
    alt: 'Ilustração de um quadro de tarefas com colunas a fazer, em andamento e concluído',
  },
  {
    kind: 'ia', name: 'IA aplicada',
    lead: 'Inteligência artificial onde ela economiza tempo de verdade, não como enfeite.',
    points: ['Assistentes que respondem com base nos documentos e dados da empresa', 'Resumo, classificação e triagem de textos e atendimentos', 'Automação de tarefas repetitivas dentro dos sistemas que você já usa'],
    alt: 'Ilustração de uma conversa em que um assistente resume chamados usando os dados da empresa',
  },
  {
    kind: 'data', name: 'Interface e dados',
    lead: 'O que faz um sistema ser usado todo dia: telas claras e dados confiáveis.',
    points: ['Desenho de telas e fluxos pensando em quem vai usar', 'Modelagem de banco de dados para o sistema crescer sem retrabalho', 'Revisão de sistemas existentes que ficaram lentos ou confusos'],
    alt: 'Ilustração de três tabelas de banco de dados ligadas entre si: clientes, pedidos e itens',
  },
];

export const situations = [
  { say: 'Tenho uma ideia e preciso de uma primeira versão', help: 'Ajudamos a recortar o essencial e construímos uma versão enxuta, para você validar com usuários reais antes de investir no resto' },
  { say: 'Minha operação vive em planilhas e mensagens', help: 'Mapeamos o processo com a sua equipe e levamos tudo para um sistema único, com histórico e permissões' },
  { say: 'Meu sistema atual travou ou ficou caro de manter', help: 'Avaliamos o código e a infraestrutura, dizemos o que vale manter e evoluímos por etapas' },
  { say: 'Quero usar IA, mas não sei por onde começar', help: 'Procuramos com você as tarefas em que ela ajuda de fato e começamos por um piloto pequeno' },
];

export const steps = [
  { name: 'Conversa', text: 'Você conta o problema e a gente pergunta até entender' },
  { name: 'Escopo', text: 'O que entra, o que fica de fora e em que ordem' },
  { name: 'Construção', text: 'Entregas em partes, para você ver funcionando cedo' },
  { name: 'Publicação', text: 'O sistema vai ao ar e passa a ser usado de verdade' },
  { name: 'Evolução', text: 'Ajustes e novas etapas conforme o uso mostra o que falta' },
];

// Conferido em 16/11/2026 nos manifestos (composer.json, package.json, linguagens, Dockerfile
// e .github/workflows) de repositórios públicos próprios em github.com/cardosoads.
// Nenhum projeto é citado. `logo` aponta para src/logos.ts; Vue fica sem logo porque a
// licença do logo (CC BY-NC-SA 4.0) não cobre uso comercial. Ver LOGOS.md.
export const stack = [
  { group: 'Servidor', text: 'Regras de negócio, APIs e integrações', items: [
    { name: 'PHP', logo: 'php' }, { name: 'Laravel', logo: 'laravel' }, { name: 'Python', logo: 'python' },
  ] },
  { group: 'Interface', text: 'O que as pessoas veem e usam', items: [
    { name: 'Vue', logo: '' }, { name: 'Inertia', logo: 'inertia' }, { name: 'React', logo: 'react' },
    { name: 'Next.js', logo: 'nextdotjs' }, { name: 'TypeScript', logo: 'typescript' }, { name: 'Tailwind CSS', logo: 'tailwindcss' },
  ] },
  { group: 'Dados e entrega', text: 'Onde o sistema guarda, roda e é publicado', items: [
    { name: 'Supabase', logo: 'supabase' }, { name: 'Docker', logo: 'docker' }, { name: 'Git', logo: 'git' }, { name: 'GitHub Actions', logo: 'githubactions' },
  ] },
];

export const brief = {
  needs: [
    { label: 'Site ou aplicação web', phrase: 'um site ou aplicação web' },
    { label: 'Produto SaaS', phrase: 'um produto SaaS' },
    { label: 'Software personalizado', phrase: 'um software personalizado' },
    { label: 'IA aplicada', phrase: 'IA aplicada ao meu negócio' },
    { label: 'Ainda não sei', phrase: 'uma solução que ainda preciso definir' },
  ],
  stages: [
    { label: 'Só a ideia', phrase: 'tenho só a ideia' },
    { label: 'Já tenho um escopo', phrase: 'já tenho um escopo definido' },
    { label: 'Já existe e precisa evoluir', phrase: 'o projeto já existe e precisa evoluir' },
  ],
};

export function composeMessage(need: string, stage: string, detail = '') {
  const extra = detail.trim().replace(/\s+/g, ' ');
  return `Olá, KDevs. Quero conversar sobre ${need}. Hoje ${stage}.${extra ? ` ${extra}` : ''}`;
}

export function whatsappUrl(message: string) {
  return site.whatsappNumber ? `https://wa.me/${site.whatsappNumber}?text=${encodeURIComponent(message)}` : '';
}
