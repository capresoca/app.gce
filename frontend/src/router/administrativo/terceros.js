import {autenticado} from '../index'
export const terceros = {
  path: '/terceros',
  name: 'Terceros',
  component: () => import('@/components/administrativo/Terceros'),
  beforeEnter: autenticado
}
