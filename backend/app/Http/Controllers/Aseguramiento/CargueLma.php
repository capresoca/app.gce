<?php
namespace app\Http\Controllers\Aseguramiento;

class CargueLma
{
    
   /* public boolean importarArchivoConsolidado(FormFile archivo, String fechaInicio, String fechaFin, String descripcion, long consecutivoPeriodo, String nombreArchivo, HttpServletRequest request) throws Exception {
        
        StatelessSession desconectada = null;
        desconectada = HibernateUtil.getSessionDesconectada();
        desconectada.getTransaction().begin();
        try {
            
            List<String> listaArchivos = new ArrayList<String>();
            StringBuilder cadena = new StringBuilder();
            String carpetaArchivos = "archivos/";
            String carpetaErrores = null;
            String nombreComprimido = "comprimido";
            boolean errores = false;
            boolean errorArchivo1 = false;
            boolean errorArchivo2 = false;
            boolean errorArchivo3 = false;
            boolean errorArchivo4 = false;
            boolean leidoArchivo1 = false;
            boolean leidoArchivo2 = false;
            boolean leidoArchivo3 = false;
            boolean leidoArchivo4 = false;
            final String saltoLinea = "\r\n";
            final String carpetaRaiz = Util.path + "descarga/liquidacion/" + Util.getCarpetaUsuario(this.getUsuario()) + "_" + System.currentTimeMillis() + "/";
            final String carpetaArchivoServidor = Util.path + "descarga/liquidacion/";
            final String carpetaArchivoZip = Util.path + "descarga/liquidacion/" + "errores_generados_" + System.currentTimeMillis() + ".rar";
            final String nombreArchivo1 = ETipoArchivoLiquidacionIngresoMasivo.ARCHIVO_1_LIQUIDACION.getNombre(getLocalidad()).toUpperCase();
            final String nombreArchivo2 = ETipoArchivoLiquidacionIngresoMasivo.ARCHIVO_2_RESTITUCIONES.getNombre(getLocalidad()).toUpperCase();
            final String nombreArchivo3 = ETipoArchivoLiquidacionIngresoMasivo.ARCHIVO_3_NETO.getNombre(getLocalidad()).toUpperCase();
            final String nombreArchivo4 = ETipoArchivoLiquidacionIngresoMasivo.ARCHIVO_4_GIRO.getNombre(getLocalidad()).toUpperCase();
            
            carpetaErrores = carpetaRaiz + "errores/";
            
            Util.getValidarDirectorio(carpetaRaiz, true);
            if (Util.vacio(nombreArchivo)) {
                nombreComprimido = Util.crearArchivo(archivo, nombreComprimido, carpetaRaiz);
            }
            else {
                nombreComprimido = nombreArchivo;
            }
            if (Util.vacio(nombreArchivo)) {
                Util.setDescomprimirRutaParametro(carpetaRaiz + nombreComprimido, carpetaRaiz + carpetaArchivos);
            }
            else {
                Util.setDescomprimirRutaParametro(carpetaArchivoServidor + nombreComprimido, carpetaRaiz + carpetaArchivos);
            }
            Util.getLeerDirectorioCompleto(carpetaRaiz + carpetaArchivos, listaArchivos);
            
            if (listaArchivos.isEmpty() || listaArchivos.size() < 4) {
                cadena.append(Mensaje.getMensaje(this.getLocalidad(), "error.liquidacioningresos.cantidadarchivos")).append(saltoLinea);
            }
            
            for (String obj : listaArchivos) {
                if (obj.toUpperCase().substring(obj.lastIndexOf(File.separator) + 1).indexOf(nombreArchivo1) != -1) {
                    if (!leidoArchivo1) {
                        errorArchivo1 = this.getIngresosMasivosLiquidacionIngresos(desconectada, obj, fechaInicio, fechaFin, descripcion, carpetaErrores, nombreArchivo1, "" + consecutivoPeriodo, ETipoArchivoLiquidacionIngresoMasivo.ARCHIVO_1_LIQUIDACION.getId(), saltoLinea);
                        leidoArchivo1 = true;
                    }
                }
                else if (obj.toUpperCase().substring(obj.lastIndexOf(File.separator) + 1).indexOf(nombreArchivo2) != -1) {
                    if (!leidoArchivo2) {
                        errorArchivo2 = this.getIngresosMasivosLiquidacionIngresos(desconectada, obj, fechaInicio, fechaFin, descripcion, carpetaErrores, nombreArchivo2, "" + consecutivoPeriodo, ETipoArchivoLiquidacionIngresoMasivo.ARCHIVO_2_RESTITUCIONES.getId(), saltoLinea);
                        leidoArchivo2 = true;
                    }
                }
                else if (obj.toUpperCase().substring(obj.lastIndexOf(File.separator) + 1).indexOf(nombreArchivo3) != -1) {
                    if (!leidoArchivo3) {
                        errorArchivo3 = this.getIngresosMasivosLiquidacionIngresos(desconectada, obj, fechaInicio, fechaFin, descripcion, carpetaErrores, nombreArchivo3, "" + consecutivoPeriodo, ETipoArchivoLiquidacionIngresoMasivo.ARCHIVO_3_NETO.getId(), saltoLinea);
                        leidoArchivo3 = true;
                    }
                }
                else if (obj.toUpperCase().substring(obj.lastIndexOf(File.separator) + 1).indexOf(nombreArchivo4) != -1) {
                    if (!leidoArchivo4) {
                        errorArchivo4 = this.getIngresosMasivosLiquidacionIngresos(desconectada, obj, fechaInicio, fechaFin, descripcion, carpetaErrores, nombreArchivo4, "" + consecutivoPeriodo, ETipoArchivoLiquidacionIngresoMasivo.ARCHIVO_4_GIRO.getId(), saltoLinea);
                        leidoArchivo4 = true;
                    }
                }
                else {
                    if (cadena == null) {
                        cadena = new StringBuilder();
                    }
                    if (!errorArchivo1 && !errorArchivo2 && !errorArchivo3 && !errorArchivo4) {
                        cadena.append(Mensaje.getMensaje(getLocalidad(), "error.aseguramiento.archivosnovalidos"));
                        break;
                    }
                }
            }
            if (!leidoArchivo1 || !leidoArchivo2 || !leidoArchivo3 || !leidoArchivo4) {
                cadena.append(Mensaje.getMensaje(this.getLocalidad(), "error.aseguramiento.archivosnovalidos")).append(saltoLinea);
            }
            if ((cadena != null && cadena.length() > 0) || errorArchivo1 || errorArchivo2 || errorArchivo3 || errorArchivo4) {
                errores = true;
                
                if (cadena != null && cadena.length() > 0) {
                    Util.getValidarDirectorio(carpetaErrores, true);
                    Util.getEscribeTxt(carpetaErrores, "ERRORES_GENERALES", cadena.toString(), true);
                }
                
                File file = new File(carpetaArchivoZip);
                if (!file.exists()) {
                    file.createNewFile();
                }
                Util.getComprimirDirectorio(carpetaErrores, file.getPath());
                Util.getBorrarDirectorioCompleto(carpetaRaiz);
                request.getSession().setAttribute("nombreArchivo", "errores_comprimidos");
                request.getSession().setAttribute("rutaArchivo", file.getPath());
                file = null;
            }
            File file = new File(carpetaRaiz + carpetaArchivos);
            Util.setEliminarDirectorio(file);
            file = new File(carpetaRaiz);
            Util.setEliminarDirectorio(file);
            if (!errores) {
                
                // Busco el Periodo y le coloco l calor de sw consolidado en 1
                CtCargueLiquidacionPeriodo p = (CtCargueLiquidacionPeriodo) desconectada.get(CtCargueLiquidacionPeriodo.class, consecutivoPeriodo);
                if (p != null) {
                    p.setSwConsolidado(ESiNo.SI.getId());
                    
                }
                desconectada.update(p);
                // Guardamos el archivo en el storage
                guardarArchivoLiquidacion(archivo, ETipoArchivoLiquidacion.CONSOLIDADO, consecutivoPeriodo);
                desconectada.getTransaction().commit();
            }
            else {
                desconectada.getTransaction().rollback();
            }
            desconectada.close();
            return errores;
        }
        catch (Exception ex) {
            try {
                desconectada.getTransaction().rollback();
            }
            catch (Exception e) {
                e.printStackTrace();
            }
            try {
                desconectada.close();
            }
            catch (Exception e) {
                e.printStackTrace();
            }
            throw ex;
        }
        
    }
*/    
}

