// Confere que o HTML gerado não depende de nada que a CSP de vercel.json bloqueia.
import { readdirSync, readFileSync, statSync } from 'node:fs';
import { join } from 'node:path';

const root = new URL('../dist/', import.meta.url).pathname;
const files = [];
const walk = (dir) => { for (const name of readdirSync(dir)) { const p = join(dir, name); statSync(p).isDirectory() ? walk(p) : p.endsWith('.html') && files.push(p); } };
walk(root);

const rules = [
  ['script inline', /<script(?![^>]*\ssrc=)[^>]*>/gi],
  ['style inline', /<style[\s>]/gi],
  ['atributo style', /\sstyle=/gi],
  ['handler inline', /\son[a-z]+=/gi],
  ['recurso externo', /<(?:script|link|img|iframe|source)[^>]*\s(?:src|href)="https?:\/\/(?!kdevs\.tech)[^"]*"/gi],
];
let failures = 0;
for (const file of files) {
  const html = readFileSync(file, 'utf8');
  for (const [label, pattern] of rules) {
    const found = html.match(pattern);
    if (found) { failures += found.length; console.error(`FALHA ${label}: ${file.replace(root, '')} -> ${found[0].slice(0, 120)}`); }
  }
}
console.log(`${files.length} arquivo(s) HTML conferidos, ${failures} violação(ões) de CSP.`);
process.exit(failures ? 1 : 0);
