import '../css/addon-tool.css';
import { Buffer } from 'buffer';
import JSZip from 'jszip';
import check, { checkAddon } from 'neverball-checker';

// Make Buffer available globally for neverball-solid (binary SOL parser)
globalThis.Buffer = Buffer;

/** @typedef {import('neverball-checker').DepEntry} DepEntry */

/** @type {{ files: string[], deps: Record<string, DepEntry> } | null} */
let stockAssetsCache = null;

async function loadStockAssets() {
  if (stockAssetsCache !== null) return stockAssetsCache;
  const res = await fetch('/neverball-assets.json');
  stockAssetsCache = await res.json();
  return stockAssetsCache;
}

/**
 * Validate a Neverball addon ZIP file in the browser.
 *
 * @param {File} file
 * @returns {Promise<{valid: boolean, errors: Array, metadata: object|null, files: string[]}>}
 */
async function validate(file) {
  const [zipData, stockAssets] = await Promise.all([
    loadZip(file),
    loadStockAssets(),
  ]);

  const fileList = Object.keys(zipData);

  // Detect all set-*.txt files at ZIP root
  const setEntries = fileList.filter(p => /^set-[^/]+\.txt$/.test(p));

  if (setEntries.length === 0) {
    const rootFiles = fileList.filter(p => !p.includes('/'));
    const rootDirs  = [...new Set(fileList.filter(p => p.includes('/')).map(p => p.split('/')[0]))].map(d => d + '/');
    const allRoot   = [...rootFiles, ...rootDirs];
    return {
      valid: false,
      errors: [{ message: 'No set file found. Expected a file named set-<slug>.txt at the root of the ZIP.', found: allRoot.slice(0, 3), foundMore: allRoot.length > 3 }],
      sets: [],
      files: fileList,
    };
  }

  const readFile = (path) => zipData[path] ?? null;
  const { valid, sets: checkedSets } = checkAddon(stockAssets, fileList, readFile);

  const sets = checkedSets.map(({ setFile, slug, missingAssets }) => {
    const id = 'set-' + slug;
    const setContent = zipData[setFile].toString('utf8');
    const lines = setContent.split(/\r?\n/);
    const name = lines[0]?.trim() || id;
    const description = lines[1]?.trim() || '';

    const errors = Array.from(missingAssets.values()).map(asset => ({
      path: asset.path,
      type: asset.type,
      parent: asset.parent?.path ?? null,
      message: `Missing ${asset.type}: ${asset.path}${asset.parent ? ` (referenced by ${asset.parent.path})` : ''}`,
    }));

    return { id, slug, name, description, errors, valid: errors.length === 0 };
  });

  return { valid, errors: [], sets, files: fileList };
}

/**
 * Load all files from a ZIP into a path → Buffer map.
 *
 * @param {File} file
 * @returns {Promise<Record<string, Buffer>>}
 */
async function loadZip(file) {
  const zip = await JSZip.loadAsync(file);
  const entries = Object.entries(zip.files).filter(([, f]) => !f.dir);
  const pairs = await Promise.all(
    entries.map(async ([name, entry]) => {
      const ab = await entry.async('arraybuffer');
      return [name, Buffer.from(ab)];
    })
  );
  return Object.fromEntries(pairs);
}

window.AddonTool = { validate };
