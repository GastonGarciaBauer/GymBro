/** Misma forma que la API (contrato MVP). Rutas relativas para Netlify/hosting estático. */

const img = (file) => `/exercise_images/${file}`;

/** Orden alfabético por nombre como GET /muscle-groups */
export const STATIC_MUSCLE_GROUPS = [
  { id: 6, name: 'Abdominales' },
  { id: 4, name: 'Brazos' },
  { id: 2, name: 'Espalda' },
  { id: 5, name: 'Hombros' },
  { id: 1, name: 'Pecho' },
  { id: 3, name: 'Piernas' },
];

export const STATIC_EXERCISES = [
  {
    id: 1,
    name: 'Press de banca',
    image_url: img('press_de_banca.png'),
    description:
      'Ejercicio compuesto fundamental para pectoral mayor, deltoides anterior y tríceps. Acostado en el banco, pies firmes en el suelo y escápulas retraídas; baja la barra con control hasta el pecho y empuja hacia arriba manteniendo muñecas rectas, core estable y contacto glúteo–espalda con el banco.',
    muscle_group: { id: 1, name: 'Pecho' },
  },
  {
    id: 2,
    name: 'Sentadilla con Barra',
    image_url: img('sentadilla_con_barra.png'),
    description:
      'Ejercicio fundamental para desarrollar fuerza en cuádriceps, glúteos y core. Coloca la barra sobre tus trapecios, baja controladamente manteniendo la espalda recta hasta que tus muslos estén paralelos al suelo.',
    muscle_group: { id: 3, name: 'Piernas' },
  },
  {
    id: 3,
    name: 'Peso Muerto',
    image_url: img('peso_muerto.png'),
    description:
      'El rey de los ejercicios compuestos. Trabaja toda la cadena posterior: espalda baja, glúteos, isquiotibiales y trapecios. Mantén la espalda recta y levanta con las piernas.',
    muscle_group: { id: 2, name: 'Espalda' },
  },
  {
    id: 4,
    name: 'Dominadas',
    image_url: img('dominadas.png'),
    description:
      'Ejercicio de peso corporal excelente para desarrollar el ancho de la espalda y bíceps. Agarra la barra con las palmas hacia afuera y eleva tu cuerpo hasta que tu barbilla supere la barra.',
    muscle_group: { id: 2, name: 'Espalda' },
  },
  {
    id: 5,
    name: 'Press Militar',
    image_url: img('press_militar.png'),
    description:
      'Fundamental para desarrollar hombros fuertes y redondeados. De pie o sentado, presiona las mancuernas o barra desde los hombros hacia arriba hasta extender completamente los brazos.',
    muscle_group: { id: 5, name: 'Hombros' },
  },
  {
    id: 6,
    name: 'Curl de Bíceps',
    image_url: img('curl_de_biceps.png'),
    description:
      'El ejercicio clásico para aislar y desarrollar los bíceps. Con mancuernas o barra, flexiona los codos llevando el peso hacia los hombros mientras mantienes los codos fijos.',
    muscle_group: { id: 4, name: 'Brazos' },
  },
  {
    id: 7,
    name: 'Prensa de Piernas',
    image_url: img('prensa_de_piernas.png'),
    description:
      'Excelente para trabajar cuádriceps y glúteos con menos presión en la espalda baja. Empuja la plataforma con los pies separados al ancho de hombros.',
    muscle_group: { id: 3, name: 'Piernas' },
  },
  {
    id: 8,
    name: 'Plancha',
    image_url: img('plancha.png'),
    description:
      'Ejercicio isométrico fundamental para fortalecer el core completo. Mantén el cuerpo recto desde la cabeza hasta los talones, apoyándote en antebrazos y puntas de los pies.',
    muscle_group: { id: 6, name: 'Abdominales' },
  },
  {
    id: 9,
    name: 'Fondos en Paralelas',
    image_url: img('fondos_en_paralelas.png'),
    description:
      'Gran ejercicio para trabajar pecho inferior y tríceps. Sujétate de las barras paralelas, baja el cuerpo flexionando los codos y empuja hacia arriba.',
    muscle_group: { id: 1, name: 'Pecho' },
  },
  {
    id: 10,
    name: 'Press Francés',
    image_url: img('press_frances.png'),
    description:
      'Ejercicio de aislamiento para tríceps. Acostado en banco, baja la barra o mancuernas hacia la frente flexionando solo los codos, luego extiende completamente.',
    muscle_group: { id: 4, name: 'Brazos' },
  },
  {
    id: 11,
    name: 'Remo con Barra',
    image_url: img('remo_con_barra.png'),
    description:
      'Excelente para desarrollar grosor en la espalda media. Inclinado hacia adelante, tira de la barra hacia el abdomen manteniendo la espalda recta.',
    muscle_group: { id: 2, name: 'Espalda' },
  },
  {
    id: 12,
    name: 'Elevaciones Laterales',
    image_url: img('elevaciones_laterales.png'),
    description:
      'Perfecto para aislar el deltoides lateral y crear hombros más anchos. Con mancuernas, eleva los brazos hacia los lados hasta la altura de los hombros.',
    muscle_group: { id: 5, name: 'Hombros' },
  },
];
