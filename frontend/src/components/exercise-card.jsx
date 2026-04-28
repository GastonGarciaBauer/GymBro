import { ImageWithFallback } from "./figma/ImageWithFallback";
import styles from "./exercise-card.module.css";

/** Acepta ejercicio en forma legacy ({ image, muscleGroup }) o API ({ image_url, muscle_group }). */
function displayImageSrc(exercise) {
  const raw = exercise.image ?? exercise.image_url ?? "";
  if (!raw) return "";
  const viteDev = "http://localhost:5173";
  if (raw.startsWith(viteDev)) {
    try {
      return new URL(raw).pathname;
    } catch {
      return raw.slice(viteDev.length) || raw;
    }
  }
  return raw;
}

function displayMuscleLabel(exercise) {
  if (exercise.muscleGroup) return exercise.muscleGroup;
  const mg = exercise.muscle_group;
  if (mg && typeof mg.name === "string") return mg.name;
  return "";
}

/** Encuadre de la imagen (object-fit: cover + object-position) por nombre en catálogo. */
function normalizeNameKey(name) {
  if (!name || typeof name !== "string") return "";
  return name
    .normalize("NFD")
    .replace(/\p{M}/gu, "")
    .toLowerCase()
    .trim();
}

/**
 * Ajustes finos: default en CSS (center 42%). Valor Y más bajo = se prioriza la parte superior del PNG.
 */
function getImageObjectStyle(name) {
  const key = normalizeNameKey(name);
  if (key === "press militar") {
    return { objectPosition: "center 14%" };
  }
  if (key === "press frances") {
    return { objectPosition: "center 30%" };
  }
  if (key === "dominadas") {
    return { objectPosition: "center 7%" };
  }
  return undefined;
}

export function ExerciseCard({ exercise }) {
  const imageSrc = displayImageSrc(exercise);
  const muscleLabel = displayMuscleLabel(exercise);
  const imageObjectStyle = getImageObjectStyle(exercise.name);

  return (
    <div className={styles.wrap}>
      <div className={styles.glow} aria-hidden />

      <div className={styles.inner}>
        <svg
          className={styles.patternSvg}
          xmlns="http://www.w3.org/2000/svg"
          aria-hidden
        >
          <defs>
            <pattern
              id={`circuit-${exercise.id}`}
              x="0"
              y="0"
              width="40"
              height="40"
              patternUnits="userSpaceOnUse"
            >
              <circle cx="2" cy="2" r="1" fill="#0095ff" />
              <line x1="2" y1="2" x2="20" y2="2" stroke="#0095ff" strokeWidth="0.5" />
              <line x1="20" y1="2" x2="20" y2="20" stroke="#00d4ff" strokeWidth="0.5" />
            </pattern>
          </defs>
          <rect width="100%" height="100%" fill={`url(#circuit-${exercise.id})`} />
        </svg>

        <div className={styles.media}>
          <ImageWithFallback
            src={imageSrc}
            alt={exercise.name}
            className={styles.mediaImg}
            style={imageObjectStyle}
          />
          <div className={styles.mediaOverlay} aria-hidden />
          <div className={styles.cornerTl} aria-hidden />
          <div className={styles.cornerTr} aria-hidden />
        </div>

        <div className={styles.body}>
          <div className={styles.metaRow}>
            <div className={styles.badgeWrap}>
              <div className={styles.badgeGlow} aria-hidden />
              <span className={styles.badge}>{muscleLabel}</span>
            </div>
          </div>
          <div className={styles.connectorTop} aria-hidden />
          <div className={styles.connectorDot} aria-hidden />
          <h3 className={styles.title}>{exercise.name}</h3>
          <div className={styles.descBlock}>
            <div className={styles.descLine} aria-hidden />
            <div className={styles.descLineDot} aria-hidden />
            <p className={styles.desc}>{exercise.description}</p>
          </div>
          <div className={styles.footer}>
            <div className={styles.footerActive}>
              <div className={styles.activeDot} aria-hidden />
              <span>ACTIVE</span>
            </div>
            <div className={styles.footerLine} aria-hidden />
            <span className={styles.footerId}>EX-{exercise.id}</span>
          </div>
        </div>

        <div className={styles.cornerBl} aria-hidden />
        <div className={styles.cornerBr} aria-hidden />
      </div>
    </div>
  );
}
