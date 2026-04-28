/**
 * Replica las reglas del MVP en PHP: AND entre búsqueda por nombre y muscle_group_id, límite, orden por nombre (es).
 */
export function filterStaticExercises(exercises, { searchTrimmed, muscleGroupKey }) {
  const limit = 50;
  let list = [...exercises];

  if (muscleGroupKey && muscleGroupKey !== 'todos') {
    const id = Number(muscleGroupKey, 10);
    if (!Number.isNaN(id) && id > 0) {
      list = list.filter((e) => e.muscle_group.id === id);
    }
  }

  if (searchTrimmed) {
    const q = searchTrimmed.toLowerCase();
    list = list.filter((e) => e.name.toLowerCase().includes(q));
  }

  list.sort((a, b) => a.name.localeCompare(b.name, 'es'));

  return list.slice(0, limit);
}
