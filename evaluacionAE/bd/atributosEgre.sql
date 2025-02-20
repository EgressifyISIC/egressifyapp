CREATE TABLE IF NOT EXISTS docente(
    idDocente INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255),
    apellidos VARCHAR(255),
    correo VARCHAR(255),
    matricula INT UNIQUE,
    password VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS asignatura(
    idAsignaturas INT AUTO_INCREMENT PRIMARY KEY,
    asignatura VARCHAR(255),
    claveAsignatura VARCHAR(255),
    claveGrupo VARCHAR(255),
    codigoAsignatura VARCHAR(255) UNIQUE,
    idDocente INT,
    FOREIGN KEY (idDocente) REFERENCES docente(idDocente)
);

CREATE TABLE IF NOT EXISTS atributoE(
    idAtributoE INT AUTO_INCREMENT PRIMARY KEY,
    logro INT,
    meta INT,
    idAsignaturas INT,
    atributoE VARCHAR(255),
    FOREIGN KEY (idAsignaturas) REFERENCES asignatura(idAsignaturas)
);

CREATE TABLE IF NOT EXISTS estudiante(
    idEstudiante INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255),
    apellido VARCHAR(255),
    correo VARCHAR(255),
    matricula VARCHAR(255) UNIQUE,
    password VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS estudiante_asignatura(
    idEstudiante INT,
    codigoAsignatura VARCHAR(255),
    FOREIGN KEY (idEstudiante) REFERENCES estudiante(idEstudiante),
    FOREIGN KEY (codigoAsignatura) REFERENCES asignatura(codigoAsignatura)
);

CREATE TABLE IF NOT EXISTS criteriosEval(
    idCriterio INT AUTO_INCREMENT PRIMARY KEY,
    indicadorEspecifico VARCHAR(255),
    nivel VARCHAR(255),
    idAtributoE INT,
    puntos INT,
    FOREIGN KEY (idAtributoE) REFERENCES atributoE(idAtributoE)
);

CREATE TABLE IF NOT EXISTS calificacion(
    idCalificacion INT AUTO_INCREMENT PRIMARY KEY,
    idEstudiante INT,
    idCriterio INT,
    calificacion INT,
    FOREIGN KEY (idEstudiante) REFERENCES estudiante(idEstudiante),
    FOREIGN KEY (idCriterio) REFERENCES criteriosEval(idCriterio)
);
