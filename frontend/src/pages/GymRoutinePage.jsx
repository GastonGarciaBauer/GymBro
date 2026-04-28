import { useState, useEffect, useMemo } from 'react'
import { Search } from 'lucide-react'
import { ExerciseCard } from '../components/exercise-card'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '../components/ui/select'
import { STATIC_MUSCLE_GROUPS, STATIC_EXERCISES } from '../data/staticExerciseCatalog'
import { filterStaticExercises } from '../lib/filterExerciseCatalog'
import styles from './GymRoutinePage.module.css'

const API_BASE =
  import.meta.env.VITE_API_BASE ?? 'http://localhost:8080/GymBro/backend/index.php'

function embeddedCatalogRequested() {
  const v = String(import.meta.env.VITE_STATIC_CATALOG ?? '')
    .toLowerCase()
    .trim()
  return v === 'true' || v === '1' || v === 'yes'
}

export default function GymRoutinePage() {
  const USE_STATIC_CATALOG = embeddedCatalogRequested()

  const [searchTerm, setSearchTerm] = useState('')
  const [selectedMuscleGroup, setSelectedMuscleGroup] = useState('todos')
  const [muscleGroups, setMuscleGroups] = useState(() =>
    USE_STATIC_CATALOG ? STATIC_MUSCLE_GROUPS : []
  )
  const [fetchedExercises, setFetchedExercises] = useState([])
  const [loadingGroups, setLoadingGroups] = useState(!USE_STATIC_CATALOG)
  const [loadingExercises, setLoadingExercises] = useState(!USE_STATIC_CATALOG)

  const exercises = useMemo(() => {
    if (USE_STATIC_CATALOG) {
      return filterStaticExercises(STATIC_EXERCISES, {
        searchTrimmed: searchTerm.trim(),
        muscleGroupKey: selectedMuscleGroup,
      })
    }
    return fetchedExercises
  }, [
    USE_STATIC_CATALOG,
    searchTerm,
    selectedMuscleGroup,
    fetchedExercises,
  ])

  const selectItems = useMemo(
    () => [
      { value: 'todos', label: 'Todos los grupos' },
      ...muscleGroups.map((g) => ({
        value: String(g.id),
        label: g.name,
      })),
    ],
    [muscleGroups]
  )

  useEffect(() => {
    if (USE_STATIC_CATALOG) {
      setMuscleGroups(STATIC_MUSCLE_GROUPS)
      setLoadingGroups(false)
      return
    }
    const loadMuscleGroups = async () => {
      setLoadingGroups(true)
      try {
        const res = await fetch(`${API_BASE}/muscle-groups`)
        if (!res.ok) {
          throw new Error('Error al cargar los grupos musculares')
        }
        const data = await res.json()
        setMuscleGroups(Array.isArray(data.data) ? data.data : [])
      } catch (error) {
        console.error('Error al cargar los grupos musculares', error)
        setMuscleGroups([])
      } finally {
        setLoadingGroups(false)
      }
    }
    loadMuscleGroups()
  }, [USE_STATIC_CATALOG])

  useEffect(() => {
    if (USE_STATIC_CATALOG) {
      setLoadingExercises(false)
      return
    }
    const loadExercises = async () => {
      setLoadingExercises(true)
      try {
        const params = new URLSearchParams()
        if (searchTerm.trim()) params.set('search', searchTerm.trim())
        if (selectedMuscleGroup !== 'todos') {
          params.set('muscle_group_id', selectedMuscleGroup)
        }
        const query = params.toString()
        const res = await fetch(
          `${API_BASE}/exercises${query ? `?${query}` : ''}`
        )
        if (!res.ok) {
          throw new Error('Error al cargar los ejercicios')
        }
        const data = await res.json()
        setFetchedExercises(Array.isArray(data.data) ? data.data : [])
      } catch (error) {
        console.error('Error al cargar los ejercicios', error)
        setFetchedExercises([])
      } finally {
        setLoadingExercises(false)
      }
    }
    loadExercises()
  }, [USE_STATIC_CATALOG, searchTerm, selectedMuscleGroup])

  const loading = loadingGroups || loadingExercises

  return (
    <div className={styles.root}>
      <div className={styles.gridBg} aria-hidden />
      <div className={styles.blobTl} aria-hidden />
      <div className={styles.blobBr} aria-hidden />

      <div className={styles.main}>
        <header className={styles.header}>
          <h1 className={styles.logoHeading}>
            <img
              src="/brand/gym-bro-logo.png"
              alt="GYM-BRO Routine — gestión de rutinas"
              width={1024}
              height={1024}
              decoding="async"
              className={styles.logoImg}
            />
          </h1>
          <p className={styles.tagline}>
            Catálogo de ejercicios · <span className={styles.taglineVersion}>v1.0</span>
          </p>
        </header>

        <div className={styles.toolbar}>
          <div className={styles.searchOuter}>
            <div className={styles.searchGlow} aria-hidden />
            <div className={styles.searchInner}>
              <Search className={styles.searchIcon} aria-hidden />
              <input
                type="text"
                placeholder="Buscar ejercicio..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                className={styles.searchInput}
              />
            </div>
          </div>

          <div className={styles.selectOuter}>
            <div className={styles.selectInner}>
              <Select value={selectedMuscleGroup} onValueChange={setSelectedMuscleGroup}>
                <SelectTrigger className={styles.selectTrigger}>
                  <SelectValue />
                </SelectTrigger>
                <SelectContent className={styles.selectContent}>
                  {selectItems.map((group) => (
                    <SelectItem key={group.value} value={group.value} className={styles.selectItem}>
                      {group.label}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
          </div>
        </div>

        <div className={styles.results}>
          <p className={styles.resultsText}>
            {loading ? (
              <span>Cargando catálogo…</span>
            ) : (
              <>
                <span className={styles.resultsCount}>{exercises.length}</span> ejercicio
                {exercises.length !== 1 ? 's' : ''} encontrado
                {exercises.length !== 1 ? 's' : ''}
              </>
            )}
          </p>
        </div>

        <div className={styles.cardGrid}>
          {!loading &&
            exercises.map((exercise) => (
              <ExerciseCard key={exercise.id} exercise={exercise} />
            ))}
        </div>

        {!loading && exercises.length === 0 && (
          <div className={styles.empty}>
            <div className={styles.emptyBox}>
              <p className={styles.emptyTitle}>No se encontraron ejercicios</p>
              <p className={styles.emptyDesc}>Intenta con otros términos de búsqueda</p>
            </div>
          </div>
        )}
      </div>
    </div>
  )
}
