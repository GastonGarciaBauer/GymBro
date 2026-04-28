import { useState } from "react";
import styles from "./ImageWithFallback.module.css";

const ERROR_IMG_SRC =
  "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODgiIGhlaWdodD0iODgiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgc3Ryb2tlPSIjMDAwIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBvcGFjaXR5PSIuMyIgZmlsbD0ibm9uZSIgc3Ryb2tlLXdpZHRoPSIzLjciPjxyZWN0IHg9IjE2IiB5PSIxNiIgd2lkdGg9IjU2IiBoZWlnaHQ9IjU2IiByeD0iNiIvPjxwYXRoIGQ9Im0xNiA1OCAxNi0xOCAzMiAzMiIvPjxjaXJjbGUgY3g9IjUzIiBjeD0iMzUiIByPSI3Ii8+PC9zdmc+Cgo=";

function ImageWithFallback(props) {
  const [didError, setDidError] = useState(false);
  const handleError = () => {
    setDidError(true);
  };
  const { src, alt, style, className, ...rest } = props;

  if (didError) {
    return (
      <div
        className={`${styles.fallbackRoot} ${className ?? ""}`.trim()}
        style={style}
      >
        <div className={styles.fallbackInner}>
          <img
            src={ERROR_IMG_SRC}
            alt="Error loading image"
            {...rest}
            data-original-url={src}
          />
        </div>
      </div>
    );
  }

  return (
    <img
      src={src}
      alt={alt}
      className={className}
      style={style}
      {...rest}
      onError={handleError}
    />
  );
}

export { ImageWithFallback };
